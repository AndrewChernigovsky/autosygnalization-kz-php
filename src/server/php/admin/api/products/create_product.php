<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/config.php';

use DATABASE\DataBase;
use Ramsey\Uuid\Uuid;

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

if (!isset($data['title'], $data['category'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product title or category not provided.']);
  exit;
}

$tempId = $data['id'] ?? null;

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare(
    "INSERT INTO Products 
            (id, title, description, price, price_list, is_published, is_popular, gallery, category, model, currency, link, `options_filters`, `functions`, `options`, autosygnals, is_special) 
        VALUES 
            (:id, :title, :description, :price, :price_list, :is_published, :is_popular, :gallery, :category, :model, :currency, :link, :options_filters, :functions, :options, :autosygnals, :is_special)"
  );

  $uuid = 'product_' . $data['category'] . '_' . Uuid::uuid4()->toString();
  $link = "/product?category={$data['category']}&id={$uuid}";
  $galleryJson = json_encode($data['gallery'] ?? []);
  $is_popular = !empty($data['is_popular']) ? 1 : 0;

  $stmt->bindValue(':id', $uuid);
  $stmt->bindValue(':title', $data['title']);
  $stmt->bindValue(':description', $data['description'] ?? '');
  $stmt->bindValue(':price', $data['price'] ?? 0);
  $stmt->bindValue(':price_list', json_encode($data['price_list'] ?? []));
  $stmt->bindValue(':is_published', !empty($data['is_published']) ? 1 : 0, PDO::PARAM_INT);
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

  $stmt->execute();

  // --- Start of new logic: rename folders and update paths ---
  $baseUploadPath = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/';

  // Update gallery
  $gallery = $data['gallery'] ?? [];
  $tempGalleryDir = $baseUploadPath . 'products/gallery/' . $tempId;
  if (strpos($tempId, 'new_') === 0 && !empty($gallery) && is_dir($tempGalleryDir)) {
    $newGalleryDir = $baseUploadPath . 'products/gallery/' . $uuid;
    if (rename($tempGalleryDir, $newGalleryDir)) {
      $newGallery = [];
      foreach ($gallery as $imageUrl) {
        $newGallery[] = str_replace('/' . $tempId . '/', '/' . $uuid . '/', $imageUrl);
      }
      $data['gallery'] = $newGallery;
      $galleryJson = json_encode($newGallery);
      $updateStmt = $pdo->prepare("UPDATE Products SET gallery = :gallery WHERE id = :id");
      $updateStmt->execute([':gallery' => $galleryJson, ':id' => $uuid]);
    }
  }

  // Update tabs
  $tabs = $data['tabs'] ?? [];
  $tempTabsDir = $baseUploadPath . 'tabs/' . $tempId;
  if (strpos($tempId, 'new_') === 0 && !empty($tabs) && is_dir($tempTabsDir)) {
    $newTabsDir = $baseUploadPath . 'tabs/' . $uuid;
    if (rename($tempTabsDir, $newTabsDir)) {
      foreach ($tabs as $tabIndex => $tab) {
        if (isset($tab['content'])) {
          foreach ($tab['content'] as $itemIndex => $item) {
            if (!empty($item['path-icon'])) {
              $tabs[$tabIndex]['content'][$itemIndex]['path-icon'] = str_replace('/' . $tempId . '/', '/' . $uuid . '/', $item['path-icon']);
            }
          }
        }
      }
      $data['tabs'] = $tabs;
    }
  }

  // Save tabs data (either original or with updated paths)
  if (isset($data['tabs'])) {
    $tabsJson = json_encode($data['tabs']);
    $tabsStmt = $pdo->prepare("
            INSERT INTO TabsAdditionalProductsData (product_id, tabs_data) 
            VALUES (:product_id, :tabs_data)
            ON DUPLICATE KEY UPDATE tabs_data = :tabs_data
        ");
    $tabsStmt->execute([
      ':product_id' => $uuid,
      ':tabs_data' => $tabsJson
    ]);
  }
  // --- End of new logic ---

  $pdo->commit();

  $data['id'] = $uuid;
  $data['link'] = $link;
  echo json_encode($data);

} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
