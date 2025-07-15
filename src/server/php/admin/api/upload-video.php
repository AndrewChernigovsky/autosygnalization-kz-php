<?php
namespace API\ADMIN;

// Подключение автозагрузчика Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Подключение конфигурации базы данных
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

use DATABASE\InitDataBase;
use Exception;

// Функция для логирования
function logMessage($message)
{
  $logFile = __DIR__ . '/upload-log.txt';
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  logMessage('Метод запроса не POST: ' . $_SERVER['REQUEST_METHOD']);
  http_response_code(405);
  echo json_encode(['error' => 'Method Not Allowed']);
  exit;
}

logMessage('Запрос POST получен');

// Проверка наличия файла в запросе
if (!isset($_FILES['video'])) {
  logMessage('Файл video не передан в запросе');
  http_response_code(400);
  echo json_encode(['error' => 'No video file uploaded']);
  exit;
}

$file = $_FILES['video'];
logMessage('Файл передан: ' . json_encode($file));

// Получение дополнительных данных из POST
$title = $_POST['title'] ?? '';
$advantages = $_POST['advantages'] ?? '';
$buttonText = $_POST['button_text'] ?? 'Подробнее';
$buttonLink = $_POST['button_link'] ?? '#';

logMessage('Дополнительные данные: title=' . $title . ', advantages=' . $advantages . ', button_text=' . $buttonText . ', button_link=' . $buttonLink);

// Проверка на ошибки при загрузке файла
if ($file['error'] !== UPLOAD_ERR_OK) {
  logMessage('Ошибка загрузки файла: ' . $file['error']);
  http_response_code(400);
  echo json_encode(['error' => 'File upload error: ' . $file['error']]);
  exit;
}

// Проверка типа файла (только видео)
$allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
if (!in_array($file['type'], $allowedTypes)) {
  logMessage('Недопустимый тип файла: ' . $file['type']);
  http_response_code(400);
  echo json_encode(['error' => 'Invalid file type. Only video files are allowed.']);
  exit;
}

// Проверка размера файла (например, не более 100MB)
$maxSize = 100 * 1024 * 1024; // 100MB в байтах
if ($file['size'] > $maxSize) {
  logMessage('Файл слишком большой: ' . $file['size'] . ' байт');
  http_response_code(400);
  echo json_encode(['error' => 'File too large. Maximum size is 100MB.']);
  exit;
}

// Создание директории uploads, если она не существует
$baseUploadDir = __DIR__ . '/../../../uploads/';
$uploadDir = $baseUploadDir . 'slider-intro/';
logMessage('Проверка директории: ' . $uploadDir);

// Создаем базовую директорию uploads, если не существует
if (!is_dir($baseUploadDir)) {
  if (!mkdir($baseUploadDir, 0755, true)) {
    logMessage('Не удалось создать базовую директорию uploads: ' . error_get_last()['message']);
  } else {
    logMessage('Базовая директория uploads создана');
  }
}

// Создаем папку slider-intro внутри uploads
if (!is_dir($uploadDir)) {
  logMessage('Директория slider-intro не существует, попытка создания');
  if (!mkdir($uploadDir, 0755, true)) {
    logMessage('Не удалось создать директорию slider-intro: ' . error_get_last()['message']);
    // Попытка создать директорию в альтернативном месте
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/admin/uploads/slider-intro/';
    logMessage('Попытка создать альтернативную директорию: ' . $uploadDir);
    if (!is_dir($uploadDir)) {
      if (!mkdir($uploadDir, 0755, true)) {
        logMessage('Не удалось создать альтернативную директорию: ' . error_get_last()['message']);
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create upload directory']);
        exit;
      }
    }
    logMessage('Альтернативная директория создана');
  } else {
    logMessage('Директория slider-intro создана');
  }
} else {
  logMessage('Директория slider-intro уже существует');
}

// Проверка прав доступа
if (!is_writable($uploadDir)) {
  logMessage('Директория не доступна для записи');
  http_response_code(500);
  echo json_encode(['error' => 'Upload directory is not writable']);
  exit;
}
logMessage('Директория доступна для записи');

// Генерация уникального имени файла для предотвращения перезаписи
$originalName = pathinfo($file['name'], PATHINFO_FILENAME);
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$uniqueName = $originalName . '_' . uniqid() . '.' . $extension;
$uploadPath = $uploadDir . $uniqueName;
logMessage('Путь для сохранения файла: ' . $uploadPath);

// Перемещение файла в целевую директорию
if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
  $error = error_get_last();
  logMessage('Не удалось переместить файл: ' . $error['message']);
  http_response_code(500);
  echo json_encode(['error' => 'Failed to save uploaded file', 'details' => $error['message']]);
  exit;
}
logMessage('Файл успешно перемещен в: ' . $uploadPath);

// Сохранение данных в базу данных
try {
  logMessage('Попытка подключения к базе данных');
  $db = new InitDataBase();
  logMessage('Объект InitDataBase создан успешно');

  $stmt = $db->prepare("INSERT INTO Videos_intro_slider (video_filename, video_path, title, advantages, button_text, button_link) VALUES (?, ?, ?, ?, ?, ?)");
  $videoPath = '/admin/uploads/slider-intro/' . $uniqueName;

  $stmt->execute([$uniqueName, $videoPath, $title, $advantages, $buttonText, $buttonLink]);
  $insertId = $db->getPdo()->lastInsertId();
  logMessage('Данные сохранены в базу данных с ID: ' . $insertId);

} catch (Exception $e) {
  logMessage('Исключение при работе с базой данных: ' . $e->getMessage());
  logMessage('Трассировка стека: ' . $e->getTraceAsString());
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()]);
  exit;
}

// Успешная загрузка
http_response_code(200);
echo json_encode([
  'success' => true,
  'message' => 'Video uploaded successfully',
  'filename' => $uniqueName,
  'path' => '/admin/uploads/slider-intro/' . $uniqueName,
  'id' => $insertId
]);
logMessage('Ответ отправлен клиенту');
?>