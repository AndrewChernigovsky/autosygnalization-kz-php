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
use PDO;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['message' => 'Method Not Allowed']);
  exit;
}

$dbConnection = DataBase::getConnection();
$pdo = $dbConnection->getPdo();

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("SELECT * FROM add_services");
  $stmt->execute();
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $pdo->commit();

  http_response_code(200);
  echo json_encode(['main' => [], 'added' => $data]);
} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Failed to save added service: ' . $e->getMessage()]);
}
