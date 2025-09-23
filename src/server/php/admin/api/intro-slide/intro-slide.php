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

use DATABASE\DataBase;
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

  public function __construct()
  {
    try {
      $db = DataBase::getInstance();
      $this->pdo = $db->getPdo();
      $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/intro-slide/';
      if (!is_dir($this->upload_dir)) {
        mkdir($this->upload_dir, 0777, true);
      }

      // Создаем подпапку для постеров
      if (!is_dir($this->upload_dir . "posters/")) {
        mkdir($this->upload_dir . "posters/", 0777, true);
      }

    } catch (Exception $e) {
      throw new Exception("Ошибка инициализации: " . $e->getMessage());
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
    $stmt = $this->pdo->prepare("SELECT video_path, poster_path FROM Videos_intro_slider WHERE id = :id");
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

  private function createFallbackPoster($outputPath)
  {
    // Создаем простое изображение-заглушку
    $width = 800;
    $height = 450;

    $image = imagecreatetruecolor($width, $height);

    // Цвета
    $bgColor = imagecolorallocate($image, 40, 40, 70);    // Темно-синий фон
    $textColor = imagecolorallocate($image, 255, 255, 255); // Белый текст
    $accentColor = imagecolorallocate($image, 0, 150, 255); // Синий акцент

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

    imagefilledpolygon($image, $points, 3, $accentColor);

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
    if (!imagejpeg($image, $outputPath, 85)) {
      imagedestroy($image);
      throw new Exception('Не удалось сохранить постер');
    }

    imagedestroy($image);
    return true;
  }

  private function saveUploadedFile($file, $target_dir, $prefix = 'file')
  {
    if ($file['error'] !== UPLOAD_ERR_OK) {
      throw new Exception('Ошибка при загрузке файла. Код: ' . $file['error']);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . '_' . uniqid() . '.' . $ext;
    $target_path = $target_dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target_path)) {
      throw new Exception('Не удалось сохранить файл');
    }

    return [
      'filename' => $filename,
      'path' => str_replace($_SERVER['DOCUMENT_ROOT'], '', $target_path)
    ];
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
        $final_paths['video_path'] = '';
        $final_paths['video_filename'] = '';
      }
      if (!empty($post['remove_poster'])) {
        $this->deleteFile($current_paths['poster_path']);
        $final_paths['poster_path'] = '';
      }

      // 2. Handle Uploads
      if (isset($files['video']['tmp_name']) && $files['video']['error'] === UPLOAD_ERR_OK) {
        $this->deleteFile($current_paths['video_path']);
        $video_info = $this->saveUploadedFile($files['video'], $this->upload_dir, 'video');
        $final_paths['video_path'] = $video_info['path'];
        $final_paths['video_filename'] = $files['video']['name'];
      }

      if (isset($files['poster']['tmp_name']) && $files['poster']['error'] === UPLOAD_ERR_OK) {
        $this->deleteFile($current_paths['poster_path']);
        $poster_info = $this->saveUploadedFile($files['poster'], $this->upload_dir . 'posters/', 'poster');
        $final_paths['poster_path'] = $poster_info['path'];
      }

      // 3. Create poster from video if needed and GD is available
      if ($final_paths['video_path'] && !$final_paths['poster_path'] && extension_loaded('gd')) {
        try {
          $base_name = pathinfo($final_paths['video_path'], PATHINFO_FILENAME);
          $poster_filename = 'poster_' . $base_name . '.jpg';
          $poster_path = $this->upload_dir . 'posters/' . $poster_filename;

          $this->createFallbackPoster($poster_path);
          $final_paths['poster_path'] = '/server/uploads/intro-slide/posters/' . $poster_filename;
        } catch (Exception $e) {
          log_message("Не удалось создать постер: " . $e->getMessage());
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

      // Обновляем пути к файлам
      $update_fields[] = "video_path = :video_path";
      $update_params[":video_path"] = $final_paths['video_path'];

      $update_fields[] = "poster_path = :poster_path";
      $update_params[":poster_path"] = $final_paths['poster_path'];

      $update_fields[] = "video_filename = :video_filename";
      $update_params[":video_filename"] = $final_paths['video_filename'] ?? '';

      // Удаляем поле video_path_mob из БД если оно существует
      $update_fields[] = "video_path_mob = ''";

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
      log_message("Ошибка обновления слайда: " . $e->getMessage());
      return $this->error("Ошибка на сервере при обновлении слайда: " . $e->getMessage(), 500);
    }
  }

  public function createSlide($post, $files)
  {
    try {
      $this->pdo->beginTransaction();

      // Validation
      if (empty($post['title']) || empty($post['button_text']) || empty($post['button_link'])) {
        throw new Exception("Основные текстовые поля не могут быть пустыми.");
      }

      if (!isset($files['video']) || $files['video']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Видео файл обязателен для создания слайда");
      }

      $final_paths = [
        'video_path' => '',
        'poster_path' => '',
        'video_filename' => $files['video']['name']
      ];

      // Сохраняем видео
      $video_info = $this->saveUploadedFile($files['video'], $this->upload_dir, 'video');
      $final_paths['video_path'] = $video_info['path'];

      // Сохраняем постер если загружен
      if (isset($files['poster']) && $files['poster']['error'] === UPLOAD_ERR_OK) {
        $poster_info = $this->saveUploadedFile($files['poster'], $this->upload_dir . 'posters/', 'poster');
        $final_paths['poster_path'] = $poster_info['path'];
      } elseif (extension_loaded('gd')) {
        // Создаем постер-заглушку если GD доступен
        try {
          $base_name = pathinfo($final_paths['video_path'], PATHINFO_FILENAME);
          $poster_filename = 'poster_' . $base_name . '.jpg';
          $poster_path = $this->upload_dir . 'posters/' . $poster_filename;

          $this->createFallbackPoster($poster_path);
          $final_paths['poster_path'] = '/server/uploads/intro-slide/posters/' . $poster_filename;
        } catch (Exception $e) {
          log_message("Не удалось создать постер: " . $e->getMessage());
        }
      }

      // Получаем следующую позицию
      $stmt = $this->pdo->query("SELECT MAX(position) as max_position FROM Videos_intro_slider");
      $max_position = $stmt->fetchColumn();
      $new_position = ($max_position === null) ? 1 : $max_position + 1;

      // Сохраняем в БД
      $query = "INSERT INTO Videos_intro_slider (title, button_text, button_link, advantages, position, video_filename, video_path, poster_path) VALUES (:title, :button_text, :button_link, :advantages, :position, :video_filename, :video_path, :poster_path)";
      $stmt = $this->pdo->prepare($query);
      $params = [
        ':title' => $post['title'],
        ':button_text' => $post['button_text'],
        ':button_link' => $post['button_link'],
        ':advantages' => $post['advantages'] ?? '[]',
        ':position' => $new_position,
        ':video_filename' => $final_paths['video_filename'],
        ':video_path' => $final_paths['video_path'],
        ':poster_path' => $final_paths['poster_path']
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
            echo $this->updateOrder($decoded);
          } else {
            echo $this->error("Неверный JSON", 400);
          }
        } else {
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