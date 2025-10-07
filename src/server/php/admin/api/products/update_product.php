<?php

$log_file = __DIR__ . '/debug.log';
file_put_contents($log_file, "--- NEW REQUEST (update_product.php) ---\n", FILE_APPEND);

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/config.php';

  use DATABASE\DataBase;

header("Access-control-allow-origin: http://localhost:5173");
header("Access-control-allow-headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, OPTIONS, PUT');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');

$dbConnection = DataBase::getConnection();
$pdo = $dbConnection->getPdo();

$data = json_decode(file_get_contents("php://input"), true);
file_put_contents($log_file, "Received data: " . print_r($data, true) . "\n", FILE_APPEND);

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product ID not provided.']);
  exit;
}

$productId = $data['id'];

function getIconsFromTabs(array $tabs): array
{
  $icons = [];
  foreach ($tabs as $tab) {
    if (isset($tab['content']) && is_array($tab['content'])) {
      foreach ($tab['content'] as $item) {
        if (!empty($item['path-icon'])) {
          $icons[] = $item['path-icon'];
        }
      }
    }
  }
  return $icons;
}

try {
  $pdo->beginTransaction();

  // Handle gallery image deletions
  $stmt = $pdo->prepare("SELECT gallery FROM Products WHERE id = :id");
  $stmt->execute([':id' => $productId]);
  $currentProduct = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($currentProduct) {
    $currentGallery = json_decode($currentProduct['gallery'], true) ?: [];
    $newGallery = $data['gallery'] ?? [];
    $imagesToDelete = array_diff($currentGallery, $newGallery);
    foreach ($imagesToDelete as $imageUrl) {
      $filePath = $_SERVER['DOCUMENT_ROOT'] . parse_url($imageUrl, PHP_URL_PATH);
      if (file_exists($filePath)) {
        unlink($filePath);
      }
    }
  }

  // Handle tab icon deletions
  $tabsStmt = $pdo->prepare("SELECT tabs_data FROM TabsAdditionalProductsData WHERE product_id = :product_id");
  $tabsStmt->execute([':product_id' => $productId]);
  $currentTabsData = $tabsStmt->fetch(PDO::FETCH_ASSOC);

  if ($currentTabsData) {
    $currentTabs = json_decode($currentTabsData['tabs_data'], true) ?: [];
    $newTabs = $data['tabs'] ?? [];

    $currentIcons = getIconsFromTabs($currentTabs);
    $newIcons = getIconsFromTabs($newTabs);

    $iconsToDelete = array_diff($currentIcons, $newIcons);

    foreach ($iconsToDelete as $iconUrl) {
      $filePath = $_SERVER['DOCUMENT_ROOT'] . parse_url($iconUrl, PHP_URL_PATH);
      if (file_exists($filePath)) {
        unlink($filePath);
      }
    }
  }

  // Update Products table
  $productStmt = $pdo->prepare("
      UPDATE Products SET
          model = :model, title = :title, description = :description, price = :price,
          is_popular = :is_popular, is_special = :is_special, gallery = :gallery,
          category = :category, link = :link, functions = :functions, options = :options,
          options_filters = :options_filters, autosygnals = :autosygnals, price_list = :price_list
      WHERE id = :id
  ");

  $link = "/product?category={$data['category']}&id={$data['id']}";
  $productData = [
    ':id' => $productId,
    ':model' => $data['model'] ?? '',
    ':title' => $data['title'] ?? '',
    ':description' => $data['description'] ?? '',
    ':price' => $data['price'] ?? 0,
    ':price_list' => json_encode($data['price_list'] ?? []),
    ':is_popular' => !empty($data['is_popular']) ? 1 : 0,
    ':is_special' => !empty($data['is_special']) ? 1 : 0,
    ':gallery' => json_encode($data['gallery'] ?? []),
    ':category' => $data['category'] ?? 'uncategorized',
    ':link' => $link,
    ':functions' => json_encode($data['functions'] ?? []),
    ':options' => json_encode($data['options'] ?? []),
    ':options_filters' => json_encode($data['options-filters'] ?? []),
    ':autosygnals' => json_encode($data['autosygnals'] ?? []),
  ];
  $productStmt->execute($productData);

  // Update TabsAdditionalProductsData table
  if (isset($data['tabs'])) {
    $tabsJson = json_encode($data['tabs']);
    $updateTabsStmt = $pdo->prepare("
            INSERT INTO TabsAdditionalProductsData (product_id, tabs_data) 
            VALUES (:product_id, :tabs_data)
            ON DUPLICATE KEY UPDATE tabs_data = :tabs_data
        ");
    $updateTabsStmt->execute([':product_id' => $productId, ':tabs_data' => $tabsJson]);
  }

  $pdo->commit();

  http_response_code(200);
  echo json_encode(['message' => 'Product updated successfully.', 'link' => $link]);

} catch (Exception $e) {
  if ($pdo && $pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Failed to update product: ' . $e->getMessage()]);
  file_put_contents($log_file, "Error: " . $e->getMessage() . "\n", FILE_APPEND);
}
