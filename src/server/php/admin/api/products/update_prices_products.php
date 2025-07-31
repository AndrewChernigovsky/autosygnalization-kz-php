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

if (!isset($data['id']) || !isset($data['prices']) || !is_array($data['prices'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product ID or prices not provided.']);
  exit;
}

$productId = $data['id'];
$prices = $data['prices'];

try {
  $pdo->beginTransaction();

  // Получаем текущие связи для продукта
  $currentLinksStmt = $pdo->prepare('SELECT price_id FROM Products_prices WHERE product_id = :product_id');
  $currentLinksStmt->execute([':product_id' => $productId]);
  $currentPriceIds = $currentLinksStmt->fetchAll(PDO::FETCH_COLUMN);

  // Собираем новые id (если есть)
  $newPriceIds = array_filter(array_map(function ($item) {
    return $item['id'] ?? null;
  }, $prices));

  // Удаляем связи, которых нет в новом списке
  if (!empty($currentPriceIds)) {
    $idsToDelete = array_diff($currentPriceIds, $newPriceIds);
    if (!empty($idsToDelete)) {
      $in = str_repeat('?,', count($idsToDelete) - 1) . '?';
      $pdo->prepare("DELETE FROM Products_prices WHERE product_id = ? AND price_id IN ($in)")
        ->execute(array_merge([$productId], $idsToDelete));
    }
  }

  // Обработка каждой услуги
  $insertPriceStmt = $pdo->prepare("INSERT INTO Prices (content, price) VALUES (:content, :price)");
  $updatePriceStmt = $pdo->prepare("UPDATE Prices SET content = :content, price = :price WHERE id = :id");
  $insertProductPriceStmt = $pdo->prepare("INSERT INTO Products_prices (product_id, price_id) VALUES (:product_id, :price_id)");

  foreach ($prices as $item) {
    $content = isset($item['description']) ? $item['description'] : '';
    $price = isset($item['installationPrice']) ? $item['installationPrice'] : '';
    $id = isset($item['id']) ? $item['id'] : null;

    if ($id && in_array($id, $currentPriceIds)) {
      // Обновляем существующую услугу
      $updatePriceStmt->execute([':content' => $content, ':price' => $price, ':id' => $id]);
    } else {
      // Вставляем новую услугу
      $insertPriceStmt->execute([':content' => $content, ':price' => $price]);
      $id = $pdo->lastInsertId();
      $insertProductPriceStmt->execute([
        ':product_id' => $productId,
        ':price_id' => $id
      ]);
    }
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
