<?php
namespace API\ADMIN;

// Log function that uses the system's temp directory
function logMessage($message)
{
  $logFile = sys_get_temp_dir() . '/autosygnalization-kz-php-debug.log';
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($logFile, "[$timestamp] [add-slide.php] " . $message . "\n", FILE_APPEND);
}

logMessage("Script execution started.");

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

use DATABASE\InitDataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  logMessage("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
  http_response_code(405);
  echo json_encode(['error' => 'Method Not Allowed']);
  exit;
}

try {
  $db = new InitDataBase();

  // Получаем максимальную текущую позицию
  $posStmt = $db->prepare("SELECT MAX(position) as max_position FROM Videos_intro_slider");
  $posStmt->execute();
  $maxPosition = $posStmt->fetchColumn();
  $newPosition = $maxPosition !== false ? (int) $maxPosition + 1 : 1;

  $stmt = $db->prepare(
    "INSERT INTO Videos_intro_slider (video_filename, video_path, title, advantages, button_text, button_link, position) VALUES (?, ?, ?, ?, ?, ?, ?)"
  );
  // Insert a new slide with default empty values
  $stmt->execute(['', '', 'Новый слайд', '[]', 'Подробнее', '#', $newPosition]);
  $newId = $db->getPdo()->lastInsertId();

  logMessage("--- New Slide Added ---");
  logMessage("Generated new slide with ID: " . $newId);

  http_response_code(200);
  echo json_encode(['success' => true, 'id' => $newId]);
} catch (Exception $e) {
  logMessage("Error: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>