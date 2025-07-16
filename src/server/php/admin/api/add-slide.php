<?php
namespace API\ADMIN;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

use DATABASE\InitDataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method Not Allowed']);
  exit;
}

try {
  $db = new InitDataBase();
  $stmt = $db->prepare(
    "INSERT INTO Videos_intro_slider (video_filename, video_path, title, advantages, button_text, button_link, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)"
  );
  // Insert a new slide with default empty values
  $stmt->execute(['', '', 'Новый слайд', '[]', 'Подробнее', '#', TRUE]);
  $newId = $db->getPdo()->lastInsertId();

  http_response_code(200);
  echo json_encode(['success' => true, 'id' => $newId]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>