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

$isNew = strpos($data['id'], 'new-') === 0;

try {
  $pdo->beginTransaction();

  if ($isNew) {
    // Создание новой услуги
    $stmt = $pdo->prepare("INSERT INTO add_services (title, price) VALUES (:title, :price)");
    $stmt->execute([
      ':title' => $data['title'],
      ':price' => $data['price'],
    ]);
    $newId = $pdo->lastInsertId();
    $data['id'] = $newId;
  } else {
    // Обновление существующей услуги
    $stmt = $pdo->prepare("UPDATE add_services SET title = :title, price = :price WHERE id = :id");
    $stmt->execute([
      ':id' => $data['id'],
      ':title' => $data['title'],
      ':price' => $data['price'],
    ]);
  }

  $pdo->commit();

  http_response_code(200);
  echo json_encode($data);
} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Failed to save added service: ' . $e->getMessage()]);
}
