<?php

namespace API\ADMIN;

// Error handling to ensure JSON response even on fatal errors
set_exception_handler(function ($exception) {
  header('Content-Type: application/json');
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'error' => 'Server Exception: ' . $exception->getMessage(),
    'file' => $exception->getFile(),
    'line' => $exception->getLine()
  ]);
  exit;
});

set_error_handler(function ($severity, $message, $file, $line) {
  if (!(error_reporting() & $severity)) {
    return;
  }
  throw new \ErrorException($message, 0, $severity, $file, $line);
});


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

function log_message($message)
{
  $log_file = __DIR__ . '/intro-slide-debug.log';
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($log_file, "[$timestamp] " . $message . "\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit(0);
}

class IntroSlideAPI extends DataBase
{
  protected $pdo;
  private $upload_dir;
  private $ffmpeg;

  public function __construct()
  {
    try {
      $db = DataBase::getInstance();
      $this->pdo = $db->getPdo();
      $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/intro-slide/';
      if (!is_dir($this->upload_dir)) {
        mkdir($this->upload_dir, 0777, true);
      }

      try {
        $ffmpeg_path = $_SERVER['DOCUMENT_ROOT'] . '/server/ffmpeg';
        $ffprobe_path = $_SERVER['DOCUMENT_ROOT'] . '/server/ffprobe';
        log_message("FFMpeg path: " . $ffmpeg_path);
        log_message("FFProbe path: " . $ffprobe_path);


        // Добавьте в конструктор для диагностики
        log_message("PHP_OS: " . PHP_OS);
        log_message("PHP_INT_SIZE: " . PHP_INT_SIZE . " bytes");
        log_message("Architecture: " . (PHP_INT_SIZE === 8 ? 'x64' : 'x86'));

        // Проверка через PHP (без shell_exec)
        $uname = function_exists('php_uname') ? php_uname() : 'unknown';
        log_message("Uname: " . $uname);

        // проверим, какой именно бинарь запускается
        exec("which ffprobe 2>&1", $output1, $return1);
        log_message("which ffprobe: " . implode("\n", $output1) . " (return=$return1)");

        // проверим версию твоего ffprobe
        exec("$ffprobe_path -version 2>&1", $output2, $return2);
        log_message("ffprobe -version:\n" . implode("\n", $output2) . "\n(return=$return2)");

        // Проверяем существование файлов
        if (!file_exists($ffmpeg_path) || !file_exists($ffprobe_path)) {
          log_message("FFmpeg binaries not found!");
          throw new Exception("FFmpeg binaries not found!");
        }

        // Детальная проверка файлов
        if (!file_exists($ffmpeg_path)) {
          log_message("FFmpeg binary NOT FOUND at: " . $ffmpeg_path);
          throw new Exception("FFmpeg binary not found!");
        }
        if (!file_exists($ffprobe_path)) {
          log_message("FFprobe binary NOT FOUND at: " . $ffprobe_path);
          throw new Exception("FFprobe binary not found!");
        }

        // Проверка прав доступа
        log_message("FFmpeg permissions: " . substr(sprintf('%o', fileperms($ffmpeg_path)), -4));
        log_message("FFprobe permissions: " . substr(sprintf('%o', fileperms($ffprobe_path)), -4));

        // Проверка размера файлов
        log_message("FFmpeg size: " . filesize($ffmpeg_path) . " bytes");
        log_message("FFprobe size: " . filesize($ffprobe_path) . " bytes");

        // Проверка исполняемости
        if (!is_executable($ffmpeg_path)) {
          log_message("FFmpeg is NOT executable");
          // Попробуем установить права
          chmod($ffmpeg_path, 0755);
          log_message("Tried to set permissions to 755");
        }
        if (!is_executable($ffprobe_path)) {
          log_message("FFprobe is NOT executable");
          chmod($ffprobe_path, 0755);
          log_message("Tried to set permissions to 755");
        }
        $this->ffmpeg = FFMpeg::create([
          'ffmpeg.binaries' => $ffmpeg_path,
          'ffprobe.binaries' => $ffprobe_path,
          'timeout' => 3600,
          'ffmpeg.threads' => 4,
        ]);

        // $ffprobe = \FFMpeg\FFProbe::create([
        //   'ffprobe.binaries' => $ffprobe_path,
        // ]);
        // var_dump($ffprobe->getFFProbeDriver()->getProcessBuilderFactory()->getBinary());

        log_message("FFMpeg успешно инициализирован");
      } catch (Exception $e) {
        log_message("Ошибка инициализации FFMpeg: " . $e->getMessage());
        // Не бросаем исключение, а возвращаем JSON-ошибку
        $this->ffmpeg = null;
      }
    } catch (Exception $e) {
      // Если произошла критическая ошибка, возвращаем JSON-ответ
      http_response_code(500);
      echo json_encode(['success' => false, 'error' => 'Ошибка инициализации сервера: ' . $e->getMessage()]);
      exit;
    }
  }

  private function success($data, $statusCode = 200)
  {
    http_response_code($statusCode);
    return json_encode(['success' => true, 'data' => $data]);
  }

  private function error($message, $statusCode = 400)
  {
    http_response_code($statusCode);
    return json_encode(['success' => false, 'error' => $message]);
  }

  public function sendAll()
  {
    try {
      $query = "SELECT * FROM Videos_intro_slider ORDER BY position ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();
      $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      foreach ($data as &$item) {
        if (isset($item['advantages']) && is_string($item['advantages'])) {
          $item['advantages'] = json_decode($item['advantages'], true);
        }
      }
      unset($item);

      return $this->success($data);
    } catch (Exception $e) {
      log_message("Ошибка получения данных: " . $e->getMessage());
      return $this->error("Ошибка получения данных", 500);
    }
  }

  private function getVideoFilePaths($id)
  {
    $stmt = $this->pdo->prepare("SELECT video_path, poster_path, video_path_mob FROM Videos_intro_slider WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  private function deleteFile($path)
  {
    if (!$path) {
      log_message("deleteFile: path is empty or null. Skipping.");
      return;
    }

    $full_path = $_SERVER['DOCUMENT_ROOT'] . $path;
    log_message("deleteFile: Attempting to delete path: {$path} (Full path: {$full_path})");

    if (file_exists($full_path)) {
      log_message("deleteFile: File exists. Unlinking...");
      if (unlink($full_path)) {
        log_message("deleteFile: Successfully unlinked.");
      } else {
        log_message("deleteFile: FAILED to unlink. Check file permissions.");
      }
    } else {
      log_message("deleteFile: File does not exist at path.");
    }
  }

  private function processVideoWithFFmpeg($temp_path, $generatePoster = true)
  {
    if ($this->ffmpeg === null) {
      throw new Exception('FFMpeg не доступен на сервере');
    }

    $video = $this->ffmpeg->open($temp_path);
    $base_name = 'video_' . uniqid();

    $paths = [
      'video_path' => "/server/uploads/intro-slide/{$base_name}.mp4",
      'poster_path' => null, // Default to null
      'video_path_mob' => "/server/uploads/intro-slide/{$base_name}_mob.webm",
    ];

    $server_paths = [
      'video_path' => $this->upload_dir . "{$base_name}.mp4",
      'poster_path' => $this->upload_dir . "posters/{$base_name}.avif",
      'video_path_mob' => $this->upload_dir . "{$base_name}_mob.webm",
    ];

    if ($generatePoster) {
      if (!is_dir($this->upload_dir . "posters/")) {
        mkdir($this->upload_dir . "posters/", 0777, true);
      }
      $video->frame(TimeCode::fromSeconds(1))->save($server_paths['poster_path']);
      $paths['poster_path'] = "/server/uploads/intro-slide/posters/{$base_name}.avif";
    }

    $format_mp4 = new X264();
    $format_mp4->setAdditionalParameters(['-an']); // No audio
    $video->save($format_mp4, $server_paths['video_path']);

    $format_webm_mobile = new WebM();
    $format_webm_mobile->setAdditionalParameters(['-crf', '32', '-b:v', '0', '-an']);
    $video->save($format_webm_mobile, $server_paths['video_path_mob']);

    return $paths;
  }

  public function updateSlide($post, $files)
  {
    try {
      if (!isset($post['id'])) {
        return $this->error("Отсутствует ID для обновления", 400);
      }
      $id = $post['id'];
      set_time_limit(600);
      $this->pdo->beginTransaction();

      $current_paths = $this->getVideoFilePaths($id);
      $final_paths = $current_paths;
      $update_params = [':id' => $id];

      // --- File Validation ---
      if (isset($files['video']) && $files['video']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Ошибка при загрузке видео. Код: ' . $files['video']['error']);
      }
      if (isset($files['poster']) && $files['poster']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Ошибка при загрузке постера. Код: ' . $files['poster']['error']);
      }
      if (isset($files['video']) && $files['video']['size'] > 50 * 1024 * 1024) {
        throw new Exception('Размер видеофайла не должен превышать 50 МБ.');
      }
      if (isset($files['poster']) && $files['poster']['size'] > 5 * 1024 * 1024) {
        throw new Exception('Размер файла постера не должен превышать 5 МБ.');
      }
      // --- End File Validation ---

      // 1. Handle Deletions
      if (!empty($post['remove_video'])) {
        $this->deleteFile($current_paths['video_path']);
        $this->deleteFile($current_paths['video_path_mob']);
        $final_paths['video_path'] = '';
        $final_paths['video_path_mob'] = '';
      }
      if (!empty($post['remove_poster'])) {
        $this->deleteFile($current_paths['poster_path']);
        $final_paths['poster_path'] = '';
      }

      // 2. Handle Uploads
      if (isset($files['video']['tmp_name'])) {
        $this->deleteFile($current_paths['video_path']);
        $this->deleteFile($current_paths['video_path_mob']);
        $video_paths = $this->processVideoWithFFmpeg($files['video']['tmp_name'], false); // Never generate poster here
        $final_paths['video_path'] = $video_paths['video_path'];
        $final_paths['video_path_mob'] = $video_paths['video_path_mob'];
      }

      if (isset($files['poster']['tmp_name'])) {
        $this->deleteFile($current_paths['poster_path']);
        $ext = pathinfo($files['poster']['name'], PATHINFO_EXTENSION);
        $poster_filename = 'poster_' . uniqid() . '.' . $ext;
        $poster_path = '/server/uploads/intro-slide/posters/' . $poster_filename;
        if (!is_dir($this->upload_dir . "posters/")) {
          mkdir($this->upload_dir . "posters/", 0777, true);
        }
        move_uploaded_file($files['poster']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $poster_path);
        $final_paths['poster_path'] = $poster_path;
      }

      // 3. Decide on Poster Generation
      if ($final_paths['video_path'] && !$final_paths['poster_path'] && $this->ffmpeg !== null) {
        $video_full_path = $_SERVER['DOCUMENT_ROOT'] . $final_paths['video_path'];
        if (file_exists($video_full_path)) {
          $video = $this->ffmpeg->open($video_full_path);
          $base_name = pathinfo($final_paths['video_path'], PATHINFO_FILENAME);
          $poster_server_path = $this->upload_dir . "posters/{$base_name}.avif";
          if (!is_dir($this->upload_dir . "posters/")) {
            mkdir($this->upload_dir . "posters/", 0777, true);
          }
          $video->frame(TimeCode::fromSeconds(1))->save($poster_server_path);
          $final_paths['poster_path'] = "/server/uploads/intro-slide/posters/{$base_name}.avif";
        }
      }

      // 4. Prepare and Execute DB Update
      $update_fields = [];
      $fields_to_update = ['title', 'button_text', 'button_link', 'advantages'];
      foreach ($fields_to_update as $field) {
        if (isset($post[$field])) {
          $update_fields[] = "$field = :$field";
          $update_params[":$field"] = $post[$field];
        }
      }

      $path_fields = ['video_path', 'video_path_mob', 'poster_path'];
      foreach ($path_fields as $field) {
        $update_fields[] = "$field = :$field";
        $update_params[":$field"] = $final_paths[$field];
      }
      if (isset($files['video']['name'])) {
        $update_fields[] = "video_filename = :video_filename";
        $update_params[':video_filename'] = $files['video']['name'];
      } else if (!empty($post['remove_video'])) {
        $update_fields[] = "video_filename = ''";
      }


      if (!empty($update_fields)) {
        $query = "UPDATE Videos_intro_slider SET " . implode(', ', $update_fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($update_params);
      }

      $this->pdo->commit();
      return $this->success(['id' => $id, 'status' => 'updated']);
    } catch (Exception $e) {
      if ($this->pdo->inTransaction()) {
        $this->pdo->rollBack();
      }
      log_message("Ошибка обновления слайда: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
      return $this->error("Ошибка на сервере при обновлении слайда: " . $e->getMessage(), 500);
    }
  }

  public function createSlide($post, $files)
  {
    try {
      $this->pdo->beginTransaction();

      // Debug logging
      log_message("createSlide: POST data: " . print_r($post, true));
      log_message("createSlide: FILES data: " . print_r($files, true));

      // Validation (can be expanded)
      if (empty($post['title']) || empty($post['button_text']) || empty($post['button_link'])) {
        log_message("Validation failed: title='" . ($post['title'] ?? 'null') . "', button_text='" . ($post['button_text'] ?? 'null') . "', button_link='" . ($post['button_link'] ?? 'null') . "'");
        throw new Exception("Основные текстовые поля не могут быть пустыми.");
      }

      // File processing (similar to update)
      $final_paths = [
        'video_path' => '',
        'poster_path' => '',
        'video_path_mob' => '',
        'video_filename' => ''
      ];

      if (isset($files['video']['tmp_name'])) {
        $video_paths = $this->processVideoWithFFmpeg($files['video']['tmp_name'], false);
        $final_paths['video_path'] = $video_paths['video_path'];
        $final_paths['video_path_mob'] = $video_paths['video_path_mob'];
        $final_paths['video_filename'] = $files['video']['name'];
      }

      if (isset($files['poster']['tmp_name'])) {
        $ext = pathinfo($files['poster']['name'], PATHINFO_EXTENSION);
        $poster_filename = 'poster_' . uniqid() . '.' . $ext;
        $poster_path = '/server/uploads/intro-slide/posters/' . $poster_filename;
        if (!is_dir($this->upload_dir . "posters/")) {
          mkdir($this->upload_dir . "posters/", 0777, true);
        }
        move_uploaded_file($files['poster']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $poster_path);
        $final_paths['poster_path'] = $poster_path;
      }

      if ($final_paths['video_path'] && !$final_paths['poster_path'] && $this->ffmpeg !== null) {
        $video_full_path = $_SERVER['DOCUMENT_ROOT'] . $final_paths['video_path'];
        if (file_exists($video_full_path)) {
          $video = $this->ffmpeg->open($video_full_path);
          $base_name = pathinfo($final_paths['video_path'], PATHINFO_FILENAME);
          $poster_server_path = $this->upload_dir . "posters/{$base_name}.avif";
          $video->frame(TimeCode::fromSeconds(1))->save($poster_server_path);
          $final_paths['poster_path'] = "/server/uploads/intro-slide/posters/{$base_name}.avif";
        }
      }

      $stmt = $this->pdo->query("SELECT MAX(position) as max_position FROM Videos_intro_slider");
      $max_position = $stmt->fetchColumn();
      $new_position = ($max_position === null) ? 1 : $max_position + 1;

      $query = "INSERT INTO Videos_intro_slider (title, button_text, button_link, advantages, position, video_filename, video_path, poster_path, video_path_mob) VALUES (:title, :button_text, :button_link, :advantages, :position, :video_filename, :video_path, :poster_path, :video_path_mob)";
      $stmt = $this->pdo->prepare($query);
      $params = [
        ':title' => $post['title'],
        ':button_text' => $post['button_text'],
        ':button_link' => $post['button_link'],
        ':advantages' => $post['advantages'],
        ':position' => $new_position,
        ':video_filename' => $final_paths['video_filename'],
        ':video_path' => $final_paths['video_path'],
        ':poster_path' => $final_paths['poster_path'],
        ':video_path_mob' => $final_paths['video_path_mob']
      ];
      $stmt->execute($params);
      $new_id = $this->pdo->lastInsertId();

      $this->pdo->commit();
      return $this->success(['id' => $new_id, 'status' => 'created'], 201);
    } catch (Exception $e) {
      if ($this->pdo->inTransaction()) {
        $this->pdo->rollBack();
      }
      log_message("Ошибка создания слайда: " . $e->getMessage());
      return $this->error("Ошибка на сервере при создании слайда: " . $e->getMessage(), 500);
    }
  }

  public function deleteSlide($post)
  {
    try {
      if (!isset($post['id'])) {
        return $this->error("Отсутствует ID для удаления", 400);
      }
      $id = $post['id'];
      $this->pdo->beginTransaction();

      $paths = $this->getVideoFilePaths($id);
      if ($paths) {
        $this->deleteFile($paths['video_path']);
        $this->deleteFile($paths['video_path_mob']);
        $this->deleteFile($paths['poster_path']);
      }

      $stmt = $this->pdo->prepare("DELETE FROM Videos_intro_slider WHERE id = :id");
      $stmt->execute([':id' => $id]);

      $this->pdo->commit();
      return $this->success(['id' => $id, 'status' => 'deleted']);
    } catch (Exception $e) {
      if ($this->pdo->inTransaction()) {
        $this->pdo->rollBack();
      }
      log_message("Ошибка удаления слайда: " . $e->getMessage());
      return $this->error("Ошибка на сервере при удалении слайда: " . $e->getMessage(), 500);
    }
  }

  public function updateOrder($post)
  {
    try {
      if (empty($post)) {
        return $this->error("Отсутствуют данные о порядке", 400);
      }

      $orderData = $post;

      $this->pdo->beginTransaction();
      $query = "UPDATE Videos_intro_slider SET position = :position WHERE id = :id";
      $stmt = $this->pdo->prepare($query);

      foreach ($orderData as $item) {
        if (!isset($item['id']) || !isset($item['position'])) {
          throw new Exception("Неверный формат элемента порядка");
        }
        $stmt->execute([':position' => $item['position'], ':id' => $item['id']]);
      }

      $this->pdo->commit();
      return $this->success(['status' => 'order updated']);
    } catch (Exception $e) {
      if ($this->pdo->inTransaction()) {
        $this->pdo->rollBack();
      }
      log_message("Ошибка обновления порядка слайдов: " . $e->getMessage());
      return $this->error("Ошибка на сервере при обновлении порядка: " . $e->getMessage(), 500);
    }
  }

  public function handleRequest()
  {
    $method = $_SERVER['REQUEST_METHOD'];
    switch ($method) {
      case 'GET':
        echo $this->sendAll();
        break;
      case 'POST':
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
          $content = trim(file_get_contents("php://input"));
          $decoded = json_decode($content, true);

          if (is_array($decoded)) {
            // Предполагаем, что это запрос на обновление порядка
            echo $this->updateOrder($decoded);
          } else {
            echo $this->error("Неверный JSON", 400);
          }
        } else {
          // Обработка multipart/form-data
          if (isset($_POST['action']) && $_POST['action'] === 'create') {
            echo $this->createSlide($_POST, $_FILES);
          } else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            echo $this->deleteSlide($_POST);
          } else {
            echo $this->updateSlide($_POST, $_FILES);
          }
        }
        break;
    }
  }
}

$api = new IntroSlideAPI();
$api->handleRequest();



