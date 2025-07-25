<?php
namespace API\ADMIN;

// Log function that uses the system's temp directory
function logMessage($message)
{
  $logFile = sys_get_temp_dir() . '/autosygnalization-kz-php-debug.log';
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($logFile, "[$timestamp] [upload-video.php] " . $message . "\n", FILE_APPEND);
}

logMessage("Script execution started.");

// Подключение автозагрузчика Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

header('Content-Type: application/json');

use DATABASE\InitDataBase;
use Exception;

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  logMessage("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
  http_response_code(405);
  echo json_encode(['error' => 'Method Not Allowed']);
  exit;
}

// Проверка наличия файла в запросе
if (!isset($_FILES['video'])) {
  http_response_code(400);
  echo json_encode(['error' => 'No video file uploaded']);
  exit;
}

$file = $_FILES['video'];
// Получение дополнительных данных из POST
$slideId = $_POST['slide_id'] ?? null;
$title = $_POST['title'] ?? '';
$advantages = $_POST['advantages'] ?? '[]';
$buttonText = $_POST['button_text'] ?? 'Подробнее';
$buttonLink = $_POST['button_link'] ?? '#';

logMessage("--- Video Upload Request ---");
logMessage("Received slide_id: " . ($slideId ?? 'null'));
logMessage("Received title: " . $title);
logMessage("Received advantages: " . $advantages);
logMessage("Received button_text: " . $buttonText);
logMessage("Received button_link: " . $buttonLink);
logMessage("File info: " . print_r($file, true));

if (!$slideId) {
  logMessage("Error: Slide ID is required.");
  http_response_code(400);
  echo json_encode(['error' => 'Slide ID is required']);
  exit;
}

// Проверка на ошибки при загрузке файла
if ($file['error'] !== UPLOAD_ERR_OK) {
  logMessage("File upload error code: " . $file['error']);
  http_response_code(400);
  echo json_encode(['error' => 'File upload error: ' . $file['error']]);
  exit;
}

// Проверка типа файла (только видео)
$allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
if (!in_array($file['type'], $allowedTypes)) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid file type. Only video files are allowed.']);
  exit;
}

$maxSize = 100 * 1024 * 1024; // 100MB в байтах
if ($file['size'] > $maxSize) {
  logMessage('Файл слишком большой: ' . $file['size'] . ' байт');
  http_response_code(400);
  echo json_encode(['error' => 'File too large. Maximum size is 100MB.']);
  exit;
}

// Создание директории uploads, если она не существует
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/slider-intro/';
if (!is_dir($uploadDir)) {
  if (!mkdir($uploadDir, 0755, true)) {
    logMessage('Не удалось создать директорию: ' . $uploadDir);
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create upload directory']);
    exit;
  }
}

$originalName = pathinfo($file['name'], PATHINFO_FILENAME);
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$uniqueName = 'video-' . time() . '.' . $extension;
$uploadPath = $uploadDir . $uniqueName;

// Перемещение файла в целевую директорию
if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
  $error = error_get_last();
  http_response_code(500);
  echo json_encode(['error' => 'Failed to save uploaded file']);
  exit;
}

// --- FFmpeg processing for mobile version ---
$mobileExtension = 'webm';
$mobileName = 'video-' . time() . '_mob.' . $mobileExtension;
$mobileUploadPath = $uploadDir . $mobileName;

// Prepare and execute the FFmpeg command
$ffmpegCommand = 'ffmpeg -i ' . escapeshellarg($uploadPath) . ' -vf "scale=w=768:h=-2" -c:v libvpx-vp9 -crf 30 -b:v 1M -c:a libopus ' . escapeshellarg($mobileUploadPath);
shell_exec($ffmpegCommand . ' 2>&1');

$mobileVideoPath = '';
if (file_exists($mobileUploadPath)) {
  $mobileVideoPath = '/server/uploads/slider-intro/' . $mobileName;
} else {
  logMessage("FFmpeg failed to create mobile version for: " . $uploadPath);
}

// Обновление данных в базе данных
try {
  $db = new InitDataBase();

  // Удаляем старый файл видео, если он существует
  $stmt = $db->prepare("SELECT video_path FROM Videos_intro_slider WHERE id = ?");
  $stmt->execute([$slideId]);
  $oldSlide = $stmt->fetch(\PDO::FETCH_ASSOC);
  if ($oldSlide && !empty($oldSlide['video_path']) && $oldSlide['video_path'] !== ('/server/uploads/slider-intro/' . $uniqueName)) {
    $oldFilePath = $_SERVER['DOCUMENT_ROOT'] . $oldSlide['video_path'];
    if (file_exists($oldFilePath)) {
      unlink($oldFilePath);
    }
  }

  $stmt = $db->prepare("UPDATE Videos_intro_slider SET video_filename = ?, video_path = ?, video_path_mob = ?, title = ?, advantages = ?, button_text = ?, button_link = ? WHERE id = ?");
  $videoPath = '/server/uploads/slider-intro/' . $uniqueName;

  $stmt->execute([$uniqueName, $videoPath, $mobileVideoPath, $title, $advantages, $buttonText, $buttonLink, $slideId]);
} catch (Exception $e) {
  logMessage("Database error: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
  exit;
}

// Успешная загрузка
logMessage("Successfully uploaded video for slide ID: " . $slideId . ". Path: " . $videoPath);
http_response_code(200);
echo json_encode([
  'success' => true,
  'message' => 'Video uploaded and slide updated successfully',
  'filename' => $uniqueName,
  'path' => $videoPath,
  'path_mob' => $mobileVideoPath,
  'id' => (int) $slideId
]);
?>