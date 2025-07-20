<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\Database;
use DATA\TabsAdditionalData;

header("Access-control-allow-origin: http://localhost:5173");
header("Access-control-allow-headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');

$dbConnection = Database::getConnection();
$tabsData = new TabsAdditionalData();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product ID not provided.']);
  exit;
}

try {
  $stmt = $dbConnection->prepare(
    "UPDATE Products SET 
            title = :title, 
            model = :model,
            description = :description, 
            price = :price, 
            is_popular = :is_popular,
            gallery = :gallery,
            link = :link,
            category = :category_key,
            is_special = :is_special,
            `functions` = :functions,
            `options` = :options,
            `options_filters` = :options_filters,
            `autosygnals` = :autosygnals
        WHERE id = :id"
  );

  $link = "/product?category={$data['category_key']}&id={$data['id']}";

  $stmt->bindParam(':id', $data['id']);
  $stmt->bindParam(':title', $data['title']);
  $stmt->bindParam(':model', $data['model']);
  $stmt->bindParam(':description', $data['description']);
  $stmt->bindParam(':price', $data['price']);
  $stmt->bindParam(':link', $link);
  $stmt->bindParam(':category_key', $data['category_key']);

  $is_popular = !empty($data['is_popular']) ? 1 : 0;
  $stmt->bindParam(':is_popular', $is_popular);

  $is_special = !empty($data['special']) ? 1 : 0;
  $stmt->bindParam(':is_special', $is_special);

  $galleryJson = json_encode($data['gallery'] ?? []);
  $stmt->bindParam(':gallery', $galleryJson);

  $functionsJson = json_encode($data['functions'] ?? []);
  $stmt->bindParam(':functions', $functionsJson);

  $optionsJson = json_encode($data['options'] ?? []);
  $stmt->bindParam(':options', $optionsJson);

  $optionsFiltersJson = json_encode($data['options-filters'] ?? []);
  $stmt->bindParam(':options_filters', $optionsFiltersJson);

  $autosygnalsJson = json_encode($data['autosygnals'] ?? []);
  $stmt->bindParam(':autosygnals', $autosygnalsJson);

  $stmt->execute();

  if (isset($data['tabs'])) {
    $tabsUpdated = $tabsData->updateTabsForProduct($data['id'], $data['tabs']);
    if (!$tabsUpdated) {
      throw new Exception('Failed to update product tabs.');
    }
  }

  echo json_encode(['message' => 'Product updated successfully.', 'link' => $link]);

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
