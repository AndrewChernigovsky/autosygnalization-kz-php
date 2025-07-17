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

  // Получаем максимальную текущую позицию
  $posStmt = $db->prepare("SELECT MAX(position) as max_position FROM Videos_intro_slider");
  $posStmt->execute();
  $maxPosition = $posStmt->fetchColumn();
  $newPosition = $maxPosition !== false ? (int) $maxPosition + 1 : 1;

  $stmt = $db->prepare(
    "INSERT INTO Videos_intro_slider (video_filename, video_path, title, advantages, button_text, button_link, position) VALUES (?, ?, ?, ?, ?, ?, ?)"
  );
  // Insert a new slide with default empty values
  $stmt->execute(['', '', 'Новый слайd', '[]', 'Подробнее', '#', $newPosition]);
  $newId = $db->getPdo()->lastInsertId();

  http_response_code(200);
  echo json_encode(['success' => true, 'id' => $newId]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>