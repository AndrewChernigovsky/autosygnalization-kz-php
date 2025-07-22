<?php

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

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product ID not provided.']);
  exit;
}

$productId = $data['id'];

try {
  $pdo->beginTransaction();

  // 1. Update Products table with all fields
  $productStmt = $pdo->prepare("UPDATE Products SET 
        model = :model, 
        title = :title, 
        description = :description, 
        price = :price, 
        is_popular = :is_popular, 
        is_special = :is_special, 
        gallery = :gallery, 
        category = :category_key,
        link = :link,
        functions = :functions, 
        options = :options, 
        options_filters = :options_filters, 
        autosygnals = :autosygnals
        WHERE id = :id");

  $link = "/product?category={$data['category_key']}&id={$data['id']}";

  $productData = [
    ':id' => $productId,
    ':model' => $data['model'],
    ':title' => $data['title'],
    ':description' => $data['description'],
    ':price' => $data['price'],
    ':is_popular' => !empty($data['is_popular']) ? 1 : 0,
    ':is_special' => !empty($data['is_special']) ? 1 : 0,
    ':gallery' => json_encode($data['gallery'] ?? []),
    ':category_key' => $data['category_key'],
    ':link' => $link,
    ':functions' => json_encode($data['functions'] ?? []),
    ':options' => json_encode($data['options'] ?? []),
    ':options_filters' => json_encode($data['options-filters'] ?? []),
    ':autosygnals' => json_encode($data['autosygnals'] ?? []),
  ];

  $productStmt->execute($productData);

  // 2. Update or Insert into TabsAdditionalProductsData table
  if (isset($data['tabs'])) {
    $tabsJson = json_encode($data['tabs']);
    $tabsStmt = $pdo->prepare("
            INSERT INTO TabsAdditionalProductsData (product_id, tabs_data) 
            VALUES (:product_id, :tabs_data)
            ON DUPLICATE KEY UPDATE tabs_data = :tabs_data
        ");
    $tabsStmt->execute([
      ':product_id' => $productId,
      ':tabs_data' => $tabsJson
    ]);
  }

  $pdo->commit();

  http_response_code(200);
  echo json_encode(['message' => 'Product updated successfully.']);
} catch (Exception $e) {
  if ($pdo && $pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Failed to update product: ' . $e->getMessage()]);
}
