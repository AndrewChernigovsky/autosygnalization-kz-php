<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/config.php';

use DATABASE\DataBase;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');

$dbConnection = DataBase::getConnection();
$pdo = $dbConnection->getPdo(); // Use PDO directly for transactions

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['price'], $data['currency'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Price or currency not provided.']);
  exit;
}

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare(
    "INSERT INTO more_services_price 
            ( price, currency) 
        VALUES 
            (:price, :currency)"
  );

  $stmt->bindValue(':price', $data['price']);
  $stmt->bindValue(':currency', $data['currency']);


  $stmt->execute();



  $pdo->commit();

  echo json_encode(['message' => 'Price created successfully']);

} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
