<?php
namespace API\ADMIN;

require_once __DIR__ . '/../../../vendor/autoload.php';

header('Content-Type: application/json');

use DATABASE\InitDataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method Not Allowed']);
  exit;
}
error_log('XXXXXXXXXX');
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;
$title = $data['title'] ?? '';
$advantages = $data['advantages'] ?? '[]';
$buttonText = $data['button_text'] ?? 'Подробнее';
$buttonLink = $data['button_link'] ?? '#';

if (!$id) {
  http_response_code(400);
  echo json_encode(['error' => 'Slide ID is required']);
  exit;
}

try {
  $db = new InitDataBase();
  $stmt = $db->prepare(
    "UPDATE Videos_intro_slider SET title = ?, advantages = ?, button_text = ?, button_link = ? WHERE id = ?"
  );
  $stmt->execute([$title, $advantages, $buttonText, $buttonLink, $id]);

  http_response_code(200);
  echo json_encode(['success' => true, 'message' => 'Slide updated successfully']);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>