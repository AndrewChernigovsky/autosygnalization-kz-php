<?php

namespace API\ADMIN;

// CORS-заголовки
header("Access-control-allow-origin: http://localhost:5173");
header("Access-control-allow-methods: GET, POST, DELETE, OPTIONS");
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../vendor/autoload.php';
use DATABASE\DataBase;
use Exception;

// --- Helper Functions ---
function log_message($message)
{
  $log_file = sys_get_temp_dir() . '/advantage-video-debug.log';
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

  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
    $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/advantage-video/';
    if (!is_dir($this->upload_dir)) {
      mkdir($this->upload_dir, 0777, true);
    }

    log_message("Инициализация AdvantageVideoAPI");
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
    set_time_limit(300);
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
          $this->deleteFile($old_paths['title_icon']);
        }
        $update_fields[] = 'title_icon = NULL';
      } elseif (isset($files['title_icon'])) {
        $max_icon_size = 5 * 1024 * 1024;
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
      if (isset($post['remove_main_video']) && $post['remove_main_video'] == '1') {
        // Удаляем все старые видео-файлы
        $this->deleteFile($old_paths['video_poster']);
        $this->deleteFile($old_paths['video_src_mob']);
        foreach ($old_paths['sources'] as $source) {
          $this->deleteFile($source['src_path']);
        }
        // Очищаем поля в базе
        $update_fields[] = 'video_poster = NULL';
        $update_fields[] = 'video_src_mob = NULL';
        // Удаляем записи из AdvantageVideoSources
        $this->pdo->prepare("DELETE FROM AdvantageVideoSources WHERE video_id = :id")->execute([':id' => $id]);

      } elseif (isset($files['main_video'])) {
        $video_file = $files['main_video'];

        // Проверка размера видеофайла
        $max_video_size = 50 * 1024 * 1024;
        if ($video_file['size'] > $max_video_size) {
          throw new Exception('Размер видеофайла не должен превышать 50 МБ.');
        }

        if ($video_file['error'] !== UPLOAD_ERR_OK) {
          throw new Exception('Ошибка загрузки видео: ' . $video_file['error']);
        }

        // Удаляем все старые видео-файлы
        $this->deleteFile($old_paths['video_poster']);
        $this->deleteFile($old_paths['video_src_mob']);
        foreach ($old_paths['sources'] as $source) {
          $this->deleteFile($source['src_path']);
        }

        // Сохраняем новое видео
        $video_path = $this->uploadFile($files['main_video'], 'video');

        // Создаем постер-заглушку
        $poster_path = $this->createFallbackPoster(pathinfo($video_path, PATHINFO_FILENAME));

        // Обновляем пути в основной таблице
        $update_fields[] = 'video_poster = :poster';
        $params[':poster'] = $poster_path;
        $update_fields[] = 'video_src_mob = :mob';
        $params[':mob'] = $video_path; // Используем тот же путь для мобильной версии

        // Обновляем источники
        $this->pdo->prepare("DELETE FROM AdvantageVideoSources WHERE video_id = :id")->execute([':id' => $id]);
        $source_stmt = $this->pdo->prepare("INSERT INTO AdvantageVideoSources (video_id, src_path, src_type) VALUES (:video_id, :src_path, :src_type)");
        $source_stmt->execute([':video_id' => $id, ':src_path' => $video_path, ':src_type' => 'video/mp4']);
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

  public function create($post, $files)
  {
    set_time_limit(300);
    log_message("--- Create request received ---");

    try {
      $this->pdo->beginTransaction();

      // 1. Вставляем основную запись, чтобы получить ID
      $query = "INSERT INTO AdvantageVideos (title, position) VALUES (:title, 1)";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute([':title' => $post['title'] ?? '']);
      $id = $this->pdo->lastInsertId();

      if (!$id) {
        throw new Exception("Не удалось создать запись и получить ID.");
      }

      $update_fields = [];
      $params = [':id' => $id];

      // 2. Обработка иконки
      if (isset($files['title_icon'])) {
        $new_path = $this->uploadFile($files['title_icon'], 'icon');
        if ($new_path) {
          $update_fields[] = 'title_icon = :title_icon';
          $params[':title_icon'] = $new_path;
        }
      }

      // 3. Обработка основного видео
      if (isset($files['main_video'])) {
        $video_path = $this->uploadFile($files['main_video'], 'video');
        $poster_path = $this->createFallbackPoster(pathinfo($video_path, PATHINFO_FILENAME));

        $update_fields[] = 'video_poster = :poster';
        $params[':poster'] = $poster_path;
        $update_fields[] = 'video_src_mob = :mob';
        $params[':mob'] = $video_path;

        $source_stmt = $this->pdo->prepare("INSERT INTO AdvantageVideoSources (video_id, src_path, src_type) VALUES (:video_id, :src_path, :src_type)");
        $source_stmt->execute([':video_id' => $id, ':src_path' => $video_path, ':src_type' => 'video/mp4']);
      }

      // 4. Обновляем запись с путями к файлам
      if (!empty($update_fields)) {
        $query = "UPDATE AdvantageVideos SET " . implode(', ', $update_fields) . " WHERE video_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
      }

      $this->pdo->commit();
      $newVideoData = $this->getVideoById($id);
      return $this->success($newVideoData, 201);

    } catch (Exception $e) {
      $this->pdo->rollBack();
      log_message("ERROR during create: " . $e->getMessage());
      return $this->error("Ошибка на сервере при создании видео: " . $e->getMessage(), 500);
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

  private function getVideoById($id)
  {
    $query_video = "SELECT * FROM AdvantageVideos WHERE video_id = :id";
    $stmt_video = $this->pdo->prepare($query_video);
    $stmt_video->execute([':id' => $id]);
    $video = $stmt_video->fetch(\PDO::FETCH_ASSOC);

    if ($video) {
      $query_sources = "SELECT source_id, src_path, src_type FROM AdvantageVideoSources WHERE video_id = :video_id";
      $stmt_sources = $this->pdo->prepare($query_sources);
      $stmt_sources->execute([':video_id' => $video['video_id']]);
      $video['sources'] = $stmt_sources->fetchAll(\PDO::FETCH_ASSOC);
    }
    return $video;
  }

  private function createFallbackPoster($base_name)
  {
    $width = 800;
    $height = 450;

    $image = imagecreatetruecolor($width, $height);

    // Цвета
    $bgColor = imagecolorallocate($image, 40, 40, 70);
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $accentColor = imagecolorallocate($image, 0, 150, 255);

    // Заполняем фон
    imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

    // Рисуем иконку "play" (треугольник)
    $centerX = $width / 2;
    $centerY = $height / 2;

    $points = [
      $centerX - 20,
      $centerY - 30,  // Левая точка
      $centerX + 40,
      $centerY,       // Правая точка  
      $centerX - 20,
      $centerY + 30   // Нижняя точка
    ];

    // Исправленный вызов без параметра $num_points
    imagefilledpolygon($image, $points, $accentColor);

    // Добавляем текст
    $texts = [
      'VIDEO PREVIEW',
      date('Y-m-d H:i:s')
    ];

    $yPosition = $centerY + 60;
    foreach ($texts as $text) {
      $textWidth = imagefontwidth(4) * strlen($text);
      $textX = $centerX - ($textWidth / 2);
      imagestring($image, 4, $textX, $yPosition, $text, $textColor);
      $yPosition += 25;
    }

    // Сохраняем как JPEG
    $poster_filename = 'poster_' . $base_name . '.jpg';
    $poster_path = $this->upload_dir . $poster_filename;

    if (!imagejpeg($image, $poster_path, 85)) {
      imagedestroy($image);
      throw new Exception('Не удалось сохранить постер');
    }

    imagedestroy($image);
    return '/server/uploads/advantage-video/' . $poster_filename;
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
      if (isset($_POST['video_id']) && $_POST['video_id'] != '0') {
        echo $api->update($_POST, $_FILES);
      } else {
        echo $api->create($_POST, $_FILES);
      }
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
  http_response_code(500);
  echo json_encode(['success' => false, 'error' => 'Внутренняя ошибка сервера']);
}