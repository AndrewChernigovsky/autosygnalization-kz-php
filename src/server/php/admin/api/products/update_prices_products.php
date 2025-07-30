<?php

$log_file = __DIR__ . '/debug.log';
file_put_contents($log_file, "--- NEW REQUEST ---\n", FILE_APPEND);

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\Database;

header("Access-control-allow-origin: http://localhost:5173");
header("Access-control-allow-headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, OPTIONS, PUT');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');

$dbConnection = Database::getConnection();
$pdo = $dbConnection->getPdo();

$data = json_decode(file_get_contents("php://input"), true);
file_put_contents($log_file, "Received data: " . print_r($data, true) . "\n", FILE_APPEND);

if (!isset($data['id']) || !isset($data['descriptions']) || !is_array($data['descriptions'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product ID or descriptions not provided.']);
  exit;
}

$productId = $data['id'];
$descriptions = $data['descriptions'];

try {
  $pdo->beginTransaction();

  // 1. Удаляем старые связи для продукта
  $pdo->prepare("DELETE FROM Products_prices WHERE product_id = :product_id")
    ->execute([':product_id' => $productId]);

  // 2. Для каждого description создаём запись в Prices и связь
  $insertPriceStmt = $pdo->prepare("INSERT INTO Prices (content) VALUES (:content)");
  $insertProductPriceStmt = $pdo->prepare("INSERT INTO Products_prices (product_id, price_id) VALUES (:product_id, :price_id)");

  foreach ($descriptions as $desc) {
    $insertPriceStmt->execute([':content' => $desc]);
    $priceId = $pdo->lastInsertId();
    $insertProductPriceStmt->execute([
      ':product_id' => $productId,
      ':price_id' => $priceId
    ]);
  }

  $pdo->commit();

  http_response_code(200);
  echo json_encode(['message' => 'Prices updated successfully.']);
} catch (Exception $e) {
  if ($pdo && $pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Failed to update prices: ' . $e->getMessage()]);
  file_put_contents($log_file, "Error: " . $e->getMessage() . "\n", FILE_APPEND);
}
