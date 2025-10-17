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

file_put_contents($log_file, "\n\n", FILE_APPEND);
file_put_contents($log_file, "████████████████████████████████████████████████████████████████████████\n", FILE_APPEND);
file_put_contents($log_file, "🔧 [PHP] update_product.php - НОВЫЙ ЗАПРОС\n", FILE_APPEND);
file_put_contents($log_file, "████████████████████████████████████████████████████████████████████████\n", FILE_APPEND);
file_put_contents($log_file, "🕐 Время: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents($log_file, "📦 Product ID: " . ($data['id'] ?? 'НЕТ') . "\n", FILE_APPEND);
file_put_contents($log_file, "📦 Title: " . ($data['title'] ?? 'НЕТ') . "\n", FILE_APPEND);
file_put_contents($log_file, "📦 Tabs count: " . (isset($data['tabs']) && is_array($data['tabs']) ? count($data['tabs']) : 0) . "\n", FILE_APPEND);

if (isset($data['tabs']) && is_array($data['tabs'])) {
  file_put_contents($log_file, "\n📊 [PHP] tabs ПОЛУЧЕНЫ от клиента:\n", FILE_APPEND);
  foreach ($data['tabs'] as $tIdx => $tab) {
    file_put_contents($log_file, "  Вкладка [$tIdx]: " . ($tab['title'] ?? 'БЕЗ НАЗВАНИЯ') . "\n", FILE_APPEND);
    if (isset($tab['content']) && is_array($tab['content'])) {
      foreach ($tab['content'] as $iIdx => $item) {
        $icon = $item['path-icon'] ?? '';
        $status = empty($icon) ? '❌ ПУСТО' : (strpos($icon, 'blob:') === 0 ? '⚠️ BLOB' : '✅ СЕРВЕР');
        file_put_contents($log_file, "    Элемент [$tIdx][$iIdx]: \"" . ($item['title'] ?? '') . "\" → $status → \"$icon\"\n", FILE_APPEND);
      }
    }
  }
}

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

  // Handle gallery image deletions and check if gallery changed
  $stmt = $pdo->prepare("SELECT gallery FROM Products WHERE id = :id");
  $stmt->execute([':id' => $productId]);
  $currentProduct = $stmt->fetch(PDO::FETCH_ASSOC);

  $galleryChanged = false;
  $galleryToSave = [];
  
  if ($currentProduct) {
    $currentGallery = json_decode($currentProduct['gallery'], true) ?: [];
    $newGallery = $data['gallery'] ?? [];
    
    // Проверяем, изменилась ли галерея
    if (json_encode($currentGallery) !== json_encode($newGallery)) {
      $galleryChanged = true;
      $galleryToSave = $newGallery;
      
      // Удаляем старые файлы, которых нет в новой галерее
      $imagesToDelete = array_diff($currentGallery, $newGallery);
      foreach ($imagesToDelete as $imageUrl) {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . parse_url($imageUrl, PHP_URL_PATH);
        if (file_exists($filePath)) {
          unlink($filePath);
        }
      }
    } else {
      // Галерея не изменилась, используем текущую из БД
      $galleryToSave = $currentGallery;
    }
  } else {
    // Если продукта нет в БД (не должно произойти), используем данные из запроса
    $galleryChanged = true;
    $galleryToSave = $data['gallery'] ?? [];
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

  // Update Products table - используем $galleryToSave вместо $data['gallery']
  $productStmt = $pdo->prepare("
      UPDATE Products SET
          model = :model, title = :title, description = :description, price = :price,
          is_published = :is_published, is_popular = :is_popular, is_special = :is_special, gallery = :gallery,
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
    ':is_published' => !empty($data['is_published']) ? 1 : 0,
    ':is_popular' => !empty($data['is_popular']) ? 1 : 0,
    ':is_special' => !empty($data['is_special']) ? 1 : 0,
    ':gallery' => json_encode($galleryToSave), // Используем проверенную галерею
    ':category' => $data['category'] ?? 'uncategorized',
    ':link' => $link,
    ':functions' => json_encode($data['functions'] ?? []),
    ':options' => json_encode($data['options'] ?? []),
    ':options_filters' => json_encode($data['options-filters'] ?? []),
    ':autosygnals' => json_encode($data['autosygnals'] ?? []),
  ];
  file_put_contents($log_file, "Bind data: " . print_r($productData, true) . "\n", FILE_APPEND);
  $productStmt->execute($productData);

  // Update TabsAdditionalProductsData table
  if (isset($data['tabs'])) {
    file_put_contents($log_file, "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", FILE_APPEND);
    file_put_contents($log_file, "🔀 [PHP] УМНОЕ СЛИЯНИЕ tabs_data - НАЧАЛО\n", FILE_APPEND);
    file_put_contents($log_file, "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", FILE_APPEND);
    
    // УМНОЕ СЛИЯНИЕ: читаем текущую tabs_data из БД и объединяем с новыми данными
    file_put_contents($log_file, "📊 [PHP] Читаем tabs_data из БД...\n", FILE_APPEND);
    $currentTabsStmt = $pdo->prepare("SELECT tabs_data FROM TabsAdditionalProductsData WHERE product_id = :product_id");
    $currentTabsStmt->execute([':product_id' => $productId]);
    $currentTabsResult = $currentTabsStmt->fetch(PDO::FETCH_ASSOC);
    
    $currentTabsData = $currentTabsResult ? json_decode($currentTabsResult['tabs_data'], true) : [];
    $newTabsData = $data['tabs'];
    
    file_put_contents($log_file, "\n📥 [PHP] currentTabsData (из БД):\n", FILE_APPEND);
    if (!empty($currentTabsData)) {
      foreach ($currentTabsData as $tIdx => $tab) {
        file_put_contents($log_file, "  Вкладка [$tIdx]: " . ($tab['title'] ?? '') . "\n", FILE_APPEND);
        if (isset($tab['content'])) {
          foreach ($tab['content'] as $iIdx => $item) {
            $icon = $item['path-icon'] ?? '';
            file_put_contents($log_file, "    Элемент [$tIdx][$iIdx]: \"" . ($item['title'] ?? '') . "\" → \"$icon\"\n", FILE_APPEND);
          }
        }
      }
    } else {
      file_put_contents($log_file, "  (пусто)\n", FILE_APPEND);
    }
    
    // Объединяем: используем структуру из frontend, но сохраняем path-icon из БД, если в frontend пустой
    file_put_contents($log_file, "\n🔀 [PHP] Выполняем слияние...\n", FILE_APPEND);
    $mergedCount = 0;
    foreach ($newTabsData as $tabIndex => $tab) {
      if (isset($tab['content']) && is_array($tab['content'])) {
        foreach ($tab['content'] as $itemIndex => $item) {
          // Если в БД есть path-icon, а во frontend пустой или blob - используем из БД
          if (isset($currentTabsData[$tabIndex]['content'][$itemIndex]['path-icon'])) {
            $dbPath = $currentTabsData[$tabIndex]['content'][$itemIndex]['path-icon'];
            $frontendPath = $item['path-icon'] ?? '';
            
            // Если frontend path пустой или blob URL - используем путь из БД
            if (empty($frontendPath) || strpos($frontendPath, 'blob:') === 0) {
              $newTabsData[$tabIndex]['content'][$itemIndex]['path-icon'] = $dbPath;
              $mergedCount++;
              file_put_contents($log_file, "  ✅ [PHP] Сохранен путь из БД для tab[$tabIndex][$itemIndex]: \"$dbPath\" (был: \"$frontendPath\")\n", FILE_APPEND);
            } else {
              file_put_contents($log_file, "  ℹ️ [PHP] Используем путь из frontend для tab[$tabIndex][$itemIndex]: \"$frontendPath\"\n", FILE_APPEND);
            }
          } else {
            file_put_contents($log_file, "  ℹ️ [PHP] В БД нет пути для tab[$tabIndex][$itemIndex], используем из frontend\n", FILE_APPEND);
          }
        }
      }
    }
    
    file_put_contents($log_file, "\n📊 [PHP] newTabsData ПОСЛЕ слияния (будет сохранено в БД):\n", FILE_APPEND);
    foreach ($newTabsData as $tIdx => $tab) {
      file_put_contents($log_file, "  Вкладка [$tIdx]: " . ($tab['title'] ?? '') . "\n", FILE_APPEND);
      if (isset($tab['content'])) {
        foreach ($tab['content'] as $iIdx => $item) {
          $icon = $item['path-icon'] ?? '';
          $status = empty($icon) ? '❌ ПУСТО' : (strpos($icon, 'blob:') === 0 ? '⚠️ BLOB' : '✅ СЕРВЕР');
          file_put_contents($log_file, "    Элемент [$tIdx][$iIdx]: \"" . ($item['title'] ?? '') . "\" → $status → \"$icon\"\n", FILE_APPEND);
        }
      }
    }
    file_put_contents($log_file, "\n✅ [PHP] Слияние завершено, сохранено путей из БД: $mergedCount\n", FILE_APPEND);
    
    $tabsJson = json_encode($newTabsData);
    file_put_contents($log_file, "💾 [PHP] Сохраняем tabs_data в БД...\n", FILE_APPEND);
    
    $updateTabsStmt = $pdo->prepare("
            INSERT INTO TabsAdditionalProductsData (product_id, tabs_data) 
            VALUES (:product_id, :tabs_data)
            ON DUPLICATE KEY UPDATE tabs_data = :tabs_data
        ");
    $updateTabsStmt->execute([':product_id' => $productId, ':tabs_data' => $tabsJson]);
    
    file_put_contents($log_file, "✅ [PHP] tabs_data СОХРАНЕН В БД\n", FILE_APPEND);
    file_put_contents($log_file, "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", FILE_APPEND);
  }

  $pdo->commit();
  
  file_put_contents($log_file, "\n✅ [PHP] update_product УСПЕШНО ЗАВЕРШЕН\n", FILE_APPEND);
  file_put_contents($log_file, "████████████████████████████████████████████████████████████████████████\n", FILE_APPEND);

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
