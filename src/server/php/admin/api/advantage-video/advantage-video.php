<?php

namespace API\ADMIN;

// CORS-заголовки
header("Access-control-allow-origin: http://localhost:5173");
header("Access-control-allow-methods: GET, POST, DELETE, OPTIONS");
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');



require_once __DIR__ . '/../../../../vendor/autoload.php';
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use DATABASE\DataBase;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WebM;
use Exception;

// --- Helper Functions ---
function log_message($message)
{
  $log_file = sys_get_temp_dir() . '/advantage-video-debug.log';
  error_log(print_r($log_file, true) . " - log_file");
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($log_file, "[$timestamp] " . $message . "\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit(0);
}

class AdvantageVideoAPI extends DataBase
{
  protected $pdo;
  private $upload_dir;
  private $ffmpeg;

  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
    $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/advantage-video/';
    if (!is_dir($this->upload_dir)) {
      mkdir($this->upload_dir, 0777, true);
    }

    // Добавление диагностического логирования
    log_message("Инициализация FFMpeg");

    try {
      $this->ffmpeg = FFMpeg::create();
      log_message("FFMpeg успешно инициализирован");
    } catch (Exception $e) {
      log_message("Ошибка инициализации FFMpeg: " . $e->getMessage());
      throw $e;
    }
  }

  public function getAll()
  {
    try {
      $query_videos = "SELECT * FROM AdvantageVideos ORDER BY position ASC";
      $stmt_videos = $this->pdo->prepare($query_videos);
      $stmt_videos->execute();
      $videos = $stmt_videos->fetchAll(\PDO::FETCH_ASSOC);

      $query_sources = "SELECT source_id, src_path, src_type FROM AdvantageVideoSources WHERE video_id = :video_id";
      $stmt_sources = $this->pdo->prepare($query_sources);

      foreach ($videos as &$video) {
        $stmt_sources->execute([':video_id' => $video['video_id']]);
        $video['sources'] = $stmt_sources->fetchAll(\PDO::FETCH_ASSOC);
      }
      unset($video);

      return $this->success($videos);

    } catch (Exception $e) {
      error_log("Ошибка получения данных 'Видео преимуществ': " . $e->getMessage());
      return $this->error("Ошибка получения данных", 500);
    }
  }

  public function update($post, $files)
  {
    set_time_limit(300); // Устанавливаем лимит выполнения в 5 минут
    log_message("--- Update request received ---");
    log_message("POST data: " . print_r($post, true));
    log_message("FILES data: " . print_r($files, true));

    if (!isset($post['video_id'])) {
      return $this->error("Отсутствует ID видео для обновления", 400);
    }
    $id = $post['video_id'];

    try {
      $this->pdo->beginTransaction();

      $old_paths = $this->getVideoFilePaths($id);

      $update_fields = [];
      $params = [':id' => $id];

      if (isset($post['title'])) {
        $update_fields[] = 'title = :title';
        $params[':title'] = $post['title'];
      }

      // Обработка иконки
      if (isset($post['remove_title_icon']) && $post['remove_title_icon'] == '1') {
        if ($old_paths['title_icon']) {
          $files_to_delete_on_success[] = $old_paths['title_icon'];
        }
        $update_fields[] = 'title_icon = NULL';
      } elseif (isset($files['title_icon'])) {
        // Проверка размера файла иконки
        $max_icon_size = 5 * 1024 * 1024; // 5 MB
        if ($files['title_icon']['size'] > $max_icon_size) {
          throw new Exception('Размер файла иконки не должен превышать 5 МБ.');
        }
        $new_path = $this->uploadFile($files['title_icon'], 'icon');
        if ($new_path) {
          $this->deleteFile($old_paths['title_icon']);
          $update_fields[] = 'title_icon = :title_icon';
          $params[':title_icon'] = $new_path;
        }
      }

      // Обработка основного видео
      if (isset($files['main_video'])) {
        $video_file = $files['main_video'];

        // Проверка размера видеофайла
        $max_video_size = 25 * 1024 * 1024; // 25 MB
        if ($video_file['size'] > $max_video_size) {
          throw new Exception('Размер видеофайла не должен превышать 25 МБ.');
        }

        if ($video_file['error'] !== UPLOAD_ERR_OK) {
          throw new Exception('Ошибка загрузки видео: ' . $video_file['error']);
        }

        $temp_video_path = $video_file['tmp_name'];
        log_message("Temporary video path: {$temp_video_path}");

        // Удаляем все старые видео-файлы
        $this->deleteFile($old_paths['video_poster']);
        $this->deleteFile($old_paths['video_src_mob']);
        foreach ($old_paths['sources'] as $source) {
          $this->deleteFile($source['src_path']);
        }

        // Создаем новые файлы
        $paths = $this->processVideoWithFFmpeg($temp_video_path);

        // Обновляем пути в основной таблице
        $update_fields[] = 'video_poster = :poster';
        $params[':poster'] = $paths['poster'];
        $update_fields[] = 'video_src_mob = :mob';
        $params[':mob'] = $paths['mob'];

        // Обновляем источники
        $this->pdo->prepare("DELETE FROM AdvantageVideoSources WHERE video_id = :id")->execute([':id' => $id]);
        $source_stmt = $this->pdo->prepare("INSERT INTO AdvantageVideoSources (video_id, src_path, src_type) VALUES (:video_id, :src_path, :src_type)");
        $source_stmt->execute([':video_id' => $id, ':src_path' => $paths['desktop_webm'], ':src_type' => 'video/webm']);
        $source_stmt->execute([':video_id' => $id, ':src_path' => $paths['desktop_mp4'], ':src_type' => 'video/mp4']);
      }

      if (!empty($update_fields)) {
        $query = "UPDATE AdvantageVideos SET " . implode(', ', $update_fields) . " WHERE video_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        log_message("Database updated successfully for video_id: {$id}");
      }

      $this->pdo->commit();
      return $this->success(["status" => "updated"]);

    } catch (Exception $e) {
      $this->pdo->rollBack();
      log_message("ERROR during update: " . $e->getMessage());
      return $this->error("Ошибка на сервере при обновлении видео: " . $e->getMessage(), 500);
    }
  }

  public function delete($id)
  {
    if (!$id) {
      return $this->error("Отсутствует ID для удаления", 400);
    }

    try {
      $this->pdo->beginTransaction();
      $stmt_main = $this->pdo->prepare("SELECT title_icon, video_poster, video_src_mob FROM AdvantageVideos WHERE video_id = :id");
      $stmt_main->execute([':id' => $id]);
      $main_files = $stmt_main->fetch(\PDO::FETCH_ASSOC);

      $stmt_sources = $this->pdo->prepare("SELECT src_path FROM AdvantageVideoSources WHERE video_id = :id");
      $stmt_sources->execute([':id' => $id]);
      $source_files = $stmt_sources->fetchAll(\PDO::FETCH_COLUMN);

      $files_to_delete = $main_files ? array_values($main_files) : [];
      $files_to_delete = array_merge($files_to_delete, $source_files);

      $stmt_delete = $this->pdo->prepare("DELETE FROM AdvantageVideos WHERE video_id = :id");
      $stmt_delete->execute([':id' => $id]);

      if ($stmt_delete->rowCount() > 0) {
        foreach ($files_to_delete as $file_path) {
          $this->deleteFile($file_path);
        }
        $this->pdo->commit();
        return $this->success(['deleted_id' => $id]);
      } else {
        $this->pdo->rollBack();
        return $this->error("Видео с таким ID не найдено", 404);
      }

    } catch (Exception $e) {
      $this->pdo->rollBack();
      error_log("Ошибка удаления видео: " . $e->getMessage());
      return $this->error("Ошибка на сервере при удалении видео", 500);
    }
  }

  private function uploadFile($file, $prefix)
  {
    if ($file['error'] !== UPLOAD_ERR_OK)
      return null;
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . '_' . uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $this->upload_dir . $filename)) {
      return '/server/uploads/advantage-video/' . $filename;
    }
    return null;
  }

  private function deleteFile($path)
  {
    if ($path && file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
      unlink($_SERVER['DOCUMENT_ROOT'] . $path);
    }
  }

  private function getVideoFilePaths($id)
  {
    $paths = ['sources' => []];
    $stmt_main = $this->pdo->prepare("SELECT title_icon, video_poster, video_src_mob FROM AdvantageVideos WHERE video_id = :id");
    $stmt_main->execute([':id' => $id]);
    $main_files = $stmt_main->fetch(\PDO::FETCH_ASSOC);
    if ($main_files) {
      $paths = array_merge($paths, $main_files);
    }

    $stmt_sources = $this->pdo->prepare("SELECT src_path FROM AdvantageVideoSources WHERE video_id = :id");
    $stmt_sources->execute([':id' => $id]);
    $paths['sources'] = $stmt_sources->fetchAll(\PDO::FETCH_ASSOC);

    return $paths;
  }

  private function processVideoWithFFmpeg($temp_path)
  {
    try {
      $video = $this->ffmpeg->open($temp_path);

      // Проверка на наличие видеодорожки
      if ($video->getStreams()->videos()->count() === 0) {
        throw new Exception('Загруженный файл не является видео или не содержит видеодорожек.');
      }

      $original_dimensions = $video->getStreams()->videos()->first()->getDimensions();

      log_message("Начало обработки видео: " . $original_dimensions->getWidth() . "x" . $original_dimensions->getHeight());

      $base_name = 'video_' . uniqid();

      $paths = [
        'poster' => "/server/uploads/advantage-video/{$base_name}.avif",
        'mob' => "/server/uploads/advantage-video/{$base_name}_mob.webm",
        'desktop_webm' => "/server/uploads/advantage-video/{$base_name}_desktop.webm",
        'desktop_mp4' => "/server/uploads/advantage-video/{$base_name}_desktop.mp4",
      ];

      $server_paths = [
        'poster' => $this->upload_dir . "{$base_name}.avif",
        'mob' => $this->upload_dir . "{$base_name}_mob.webm",
        'desktop_webm' => $this->upload_dir . "{$base_name}_desktop.webm",
        'desktop_mp4' => $this->upload_dir . "{$base_name}_desktop.mp4",
      ];

      // 1. Создаем постер из исходного видео
      $video->frame(TimeCode::fromSeconds(1))->save($server_paths['poster']);
      log_message("Постер создан.");

      // --- Обработка десктопной версии ---
      $desktop_video = clone $video;
      $desktop_width = $original_dimensions->getWidth();
      $desktop_height = $original_dimensions->getHeight();

      if ($desktop_width > 1920) {
        log_message("Десктопное видео будет уменьшено до ширины 1920px.");
        $desktop_width = 1920;
        $desktop_height = (int) (($desktop_width / $original_dimensions->getWidth()) * $original_dimensions->getHeight());
        if ($desktop_height % 2 !== 0) {
          $desktop_height++;
        }
        $desktop_video->filters()->resize(new Dimension($desktop_width, $desktop_height))->synchronize();
      }

      $format_mp4 = new X264();
      $format_mp4->setAdditionalParameters(['-crf', '23', '-an']);
      $desktop_video->save($format_mp4, $server_paths['desktop_mp4']);
      log_message("Десктопный MP4 создан.");

      $format_webm_desktop = new WebM();
      $format_webm_desktop->setAdditionalParameters(['-crf', '28', '-b:v', '0', '-an']);
      $desktop_video->save($format_webm_desktop, $server_paths['desktop_webm']);
      log_message("Десктопный WebM создан.");

      // --- Обработка мобильной версии ---
      $mobile_video = clone $video;
      $mobile_width = $original_dimensions->getWidth();
      $mobile_height = $original_dimensions->getHeight();

      if ($mobile_width > 1080) {
        log_message("Мобильное видео будет уменьшено до ширины 1080px.");
        $mobile_width = 1080;
        $mobile_height = (int) (($mobile_width / $original_dimensions->getWidth()) * $original_dimensions->getHeight());
        if ($mobile_height % 2 !== 0) {
          $mobile_height++;
        }
        $mobile_video->filters()->resize(new Dimension($mobile_width, $mobile_height))->synchronize();
      }

      $format_webm_mobile = new WebM();
      $format_webm_mobile->setAdditionalParameters(['-crf', '32', '-b:v', '0', '-an']);
      $mobile_video->save($format_webm_mobile, $server_paths['mob']);
      log_message("Мобильный WebM создан.");

      // Финальная проверка и возврат результата
      $results = [];
      foreach (array_keys($server_paths) as $type) {
        if (file_exists($server_paths[$type])) {
          $results[$type] = $paths[$type];
          log_message("Файл {$type} успешно создан.");
        } else {
          $results[$type] = '';
          log_message("Не удалось создать файл {$type}.");
        }
      }

      return $results;

    } catch (Exception $e) {
      log_message("Ошибка обработки FFmpeg: " . $e->getMessage());
      error_log("Ошибка обработки FFmpeg: " . $e->getFile() . " " . $e->getLine() . " " . $e->getTraceAsString());
      // Возвращаем массив с пустыми путями в случае любой ошибки
      if (!isset($paths)) {
        $paths = ['poster' => '', 'mob' => '', 'desktop_webm' => '', 'desktop_mp4' => ''];
      }
      return array_fill_keys(array_keys($paths), '');
    }
  }

  private function success($data, $statusCode = 200)
  {
    http_response_code($statusCode);
    return json_encode(['success' => true, 'data' => $data]);
  }

  public function error($message, $statusCode = 400)
  {
    http_response_code($statusCode);
    return json_encode(['success' => false, 'error' => $message]);
  }
}

try {
  $api = new AdvantageVideoAPI();
  $method = $_SERVER['REQUEST_METHOD'];

  switch ($method) {
    case 'GET':
      echo $api->getAll();
      break;

    case 'POST':
      echo $api->update($_POST, $_FILES);
      break;

    case 'DELETE':
      $id = $_GET['id'] ?? null;
      echo $api->delete($id);
      break;

    default:
      echo $api->error("Метод не поддерживается", 405);
      break;
  }
} catch (Exception $e) {
  error_log("Критическая ошибка API 'Видео преимуществ': " . $e->getMessage());
  error_log("Файл ошибки: " . $e->getFile());
  error_log("Строка ошибки: " . $e->getLine());
  http_response_code(500);
  error_log("Критическая ошибка API 'Видео преимуществ': " . $e->getMessage());
  echo json_encode(['success' => false, 'error' => 'Внутренняя ошибка сервера']);
}