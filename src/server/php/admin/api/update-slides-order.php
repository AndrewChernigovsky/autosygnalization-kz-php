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

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order']) || !is_array($data['order'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid order data']);
  exit;
}

try {
  $db = new InitDataBase();
  $db->getPdo()->beginTransaction();

  $stmt = $db->prepare("UPDATE Videos_intro_slider SET position = ? WHERE id = ?");

  foreach ($data['order'] as $item) {
    if (!isset($item['id']) || !isset($item['position'])) {
      throw new Exception('Invalid item in order data');
    }
    $stmt->execute([(int) $item['position'], (int) $item['id']]);
  }

  $db->getPdo()->commit();

  http_response_code(200);
  echo json_encode(['success' => true, 'message' => 'Slides order updated successfully']);
} catch (Exception $e) {
  if ($db->getPdo()->inTransaction()) {
    $db->getPdo()->rollBack();
  }
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 