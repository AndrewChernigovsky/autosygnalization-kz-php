<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/config.php';

use DATABASE\Database;
use Ramsey\Uuid\Uuid;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');

$db = Database::getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['title'], $data['category'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product title or category not provided.']);
  exit;
}

try {
  $stmt = $db->prepare(
    "INSERT INTO Products 
            (id, title, description, price, is_popular, gallery, category, model, currency, link, `options_filters`, `functions`, `options`, autosygnals, is_special) 
        VALUES 
            (:id, :title, :description, :price, :is_popular, :gallery, :category, :model, :currency, :link, :options_filters, :functions, :options, :autosygnals, :is_special)"
  );

  $uuid = 'product_' . $data['category'] . '_' . Uuid::uuid4()->toString();
  $link = "/product?category={$data['category']}&id={$uuid}";
  $galleryJson = json_encode($data['gallery'] ?? []);
  $is_popular = !empty($data['is_popular']) ? 1 : 0;

  $stmt->bindValue(':id', $uuid);
  $stmt->bindValue(':title', $data['title']);
  $stmt->bindValue(':description', $data['description'] ?? '');
  $stmt->bindValue(':price', $data['price'] ?? 0);
  $stmt->bindValue(':is_popular', $is_popular ?? 0);
  $stmt->bindValue(':gallery', $galleryJson);
  $stmt->bindValue(':category', $data['category']);
  $stmt->bindValue(':model', $data['model'] ?? $data['title']);
  $stmt->bindValue(':currency', $data['currency'] ?? '₸');
  $stmt->bindValue(':link', $link);
  $stmt->bindValue(':options_filters', json_encode($data['options-filters'] ?? []));
  $stmt->bindValue(':functions', json_encode($data['functions'] ?? []));
  $stmt->bindValue(':options', json_encode($data['options'] ?? []));
  $stmt->bindValue(':autosygnals', json_encode($data['autosygnals'] ?? []));
  $stmt->bindValue(':is_special', $data['is_special'] ?? 0, PDO::PARAM_INT);

  if ($stmt->execute()) {
    $data['id'] = $uuid;
    $data['link'] = $link;

    if (isset($data['tabs'])) {

      $tabsJson = json_encode($data['tabs']);
      $tabsStmt = $db->prepare("
              INSERT INTO TabsAdditionalProductsData (product_id, tabs_data) 
              VALUES (:product_id, :tabs_data)
          ");
      $tabsStmt->execute([
        ':product_id' => $uuid,
        ':tabs_data' => $tabsJson
      ]);
    }

    echo json_encode($data);
  } else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to create product.']);
  }
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
