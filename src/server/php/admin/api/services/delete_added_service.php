<?php

namespace API\SERVICES\ADMIN;

// Set CORS headers
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\DataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['message' => 'Method Not Allowed']);
  exit;
}

$dbConnection = DataBase::getConnection();
$pdo = $dbConnection->getPdo();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Service ID not provided.']);
  exit;
}

$id = $data['id'];

try {
  $stmt = $pdo->prepare("DELETE FROM add_services WHERE id = :id");
  $stmt->execute([':id' => $id]);

  if ($stmt->rowCount() > 0) {
    http_response_code(200);
    echo json_encode(['message' => 'Added service deleted successfully.']);
  } else {
    http_response_code(404);
    echo json_encode(['message' => 'Service not found.']);
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['message' => 'Failed to delete added service: ' . $e->getMessage()]);
}
