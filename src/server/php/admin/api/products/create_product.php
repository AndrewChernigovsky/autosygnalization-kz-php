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

$log_file = __DIR__ . '/debug_create_product.log';

$dbConnection = DataBase::getConnection();
$pdo = $dbConnection->getPdo(); // Use PDO directly for transactions

$data = json_decode(file_get_contents("php://input"), true);

file_put_contents($log_file, "\n\n", FILE_APPEND);
file_put_contents($log_file, "████████████████████████████████████████████████████████████████████████\n", FILE_APPEND);
file_put_contents($log_file, "➕ [PHP] create_product.php - СОЗДАНИЕ НОВОГО ТОВАРА\n", FILE_APPEND);
file_put_contents($log_file, "████████████████████████████████████████████████████████████████████████\n", FILE_APPEND);
file_put_contents($log_file, "🕐 Время: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents($log_file, "📦 tempId: " . ($data['id'] ?? 'НЕТ') . "\n", FILE_APPEND);
file_put_contents($log_file, "📦 title: " . ($data['title'] ?? 'НЕТ') . "\n", FILE_APPEND);
file_put_contents($log_file, "📦 category: " . ($data['category'] ?? 'НЕТ') . "\n", FILE_APPEND);

if (isset($data['tabs']) && is_array($data['tabs'])) {
  file_put_contents($log_file, "\n📊 [PHP] tabs ПОЛУЧЕНЫ от клиента (count: " . count($data['tabs']) . "):\n", FILE_APPEND);
  foreach ($data['tabs'] as $tIdx => $tab) {
    file_put_contents($log_file, "  Вкладка [$tIdx]: " . ($tab['title'] ?? '') . "\n", FILE_APPEND);
    if (isset($tab['content'])) {
      foreach ($tab['content'] as $iIdx => $item) {
        $icon = $item['path-icon'] ?? '';
        file_put_contents($log_file, "    Элемент [$tIdx][$iIdx]: \"" . ($item['title'] ?? '') . "\" → \"$icon\"\n", FILE_APPEND);
      }
    }
  }
}

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
  
  file_put_contents($log_file, "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", FILE_APPEND);
  file_put_contents($log_file, "📂 [PHP] Обработка tabs для нового продукта\n", FILE_APPEND);
  file_put_contents($log_file, "  tempId: $tempId\n", FILE_APPEND);
  file_put_contents($log_file, "  uuid (новый ID): $uuid\n", FILE_APPEND);
  file_put_contents($log_file, "  tempTabsDir: $tempTabsDir\n", FILE_APPEND);
  file_put_contents($log_file, "  is_new: " . (strpos($tempId, 'new_') === 0 ? 'ДА' : 'НЕТ') . "\n", FILE_APPEND);
  file_put_contents($log_file, "  tabs count: " . count($tabs) . "\n", FILE_APPEND);
  file_put_contents($log_file, "  tempTabsDir полный путь: $tempTabsDir\n", FILE_APPEND);
  file_put_contents($log_file, "  dir exists: " . (is_dir($tempTabsDir) ? 'ДА' : 'НЕТ') . "\n", FILE_APPEND);
  file_put_contents($log_file, "  DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n", FILE_APPEND);
  
  // ОТЛАДКА: Выводим в error_log тоже
  error_log("CREATE_PRODUCT: tempId=$tempId, uuid=$uuid, tempTabsDir=$tempTabsDir, exists=" . (is_dir($tempTabsDir) ? 'YES' : 'NO'));
  
  if (strpos($tempId, 'new_') === 0 && !empty($tabs) && is_dir($tempTabsDir)) {
    $newTabsDir = $baseUploadPath . 'tabs/' . $uuid;
    file_put_contents($log_file, "  newTabsDir: $newTabsDir\n", FILE_APPEND);
    
    if (rename($tempTabsDir, $newTabsDir)) {
      file_put_contents($log_file, "✅ [PHP] Папка переименована успешно\n", FILE_APPEND);
      file_put_contents($log_file, "🔄 [PHP] Обновляем пути в tabs:\n", FILE_APPEND);
      
      foreach ($tabs as $tabIndex => $tab) {
        if (isset($tab['content'])) {
          foreach ($tab['content'] as $itemIndex => $item) {
            if (!empty($item['path-icon'])) {
              $oldPath = $item['path-icon'];
              $newPath = str_replace('/' . $tempId . '/', '/' . $uuid . '/', $oldPath);
              $tabs[$tabIndex]['content'][$itemIndex]['path-icon'] = $newPath;
              file_put_contents($log_file, "  [$tabIndex][$itemIndex]: \"$oldPath\" → \"$newPath\"\n", FILE_APPEND);
            }
          }
        }
      }
      $data['tabs'] = $tabs;
      file_put_contents($log_file, "✅ [PHP] Пути обновлены в tabs\n", FILE_APPEND);
    } else {
      file_put_contents($log_file, "❌ [PHP] НЕ удалось переименовать папку!\n", FILE_APPEND);
    }
  } else {
    file_put_contents($log_file, "ℹ️ [PHP] Условия для переименования НЕ выполнены, пропускаем\n", FILE_APPEND);
  }
  file_put_contents($log_file, "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", FILE_APPEND);

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
  
  file_put_contents($log_file, "\n✅ [PHP] create_product УСПЕШНО ЗАВЕРШЕН\n", FILE_APPEND);
  file_put_contents($log_file, "📦 Возвращаем клиенту новый ID: $uuid\n", FILE_APPEND);
  file_put_contents($log_file, "████████████████████████████████████████████████████████████████████████\n", FILE_APPEND);
  
  echo json_encode($data);

} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
