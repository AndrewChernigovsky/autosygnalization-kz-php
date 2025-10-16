<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\DataBase;

// Dynamic CORS to support different hosts (dev/build)
$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
header("Access-Control-Allow-Origin: " . $origin);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');

$log_file = __DIR__ . '/debug_upload_tab_icon.log';
file_put_contents($log_file, "\n\n", FILE_APPEND);
file_put_contents($log_file, "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", FILE_APPEND);
file_put_contents($log_file, "📤 [PHP] upload_tab_icon.php - НОВЫЙ ЗАПРОС\n", FILE_APPEND);
file_put_contents($log_file, "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", FILE_APPEND);
file_put_contents($log_file, "🕐 Время: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents($log_file, "📦 POST параметры:\n", FILE_APPEND);
file_put_contents($log_file, "  productId: " . ($_POST['productId'] ?? 'НЕТ') . "\n", FILE_APPEND);
file_put_contents($log_file, "  tabIndex: " . ($_POST['tabIndex'] ?? 'НЕТ') . "\n", FILE_APPEND);
file_put_contents($log_file, "  itemIndex: " . ($_POST['itemIndex'] ?? 'НЕТ') . "\n", FILE_APPEND);
file_put_contents($log_file, "📁 FILES параметры:\n", FILE_APPEND);
file_put_contents($log_file, print_r(array_map(function($f){ 
  return [ 'name'=>$f['name'], 'error'=>$f['error'], 'size'=>$f['size'], 'type'=>$f['type'] ]; 
}, $_FILES), true) . "\n", FILE_APPEND);

$dbConnection = DataBase::getConnection();
$pdo = $dbConnection->getPdo();

// Basic validation
if (!isset($_POST['productId'], $_POST['tabIndex'], $_POST['itemIndex'], $_FILES['path-icon'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Missing required parameters.']);
  exit;
}

$productId = $_POST['productId'];
$tabIndex = (int) $_POST['tabIndex'];
$itemIndex = (int) $_POST['itemIndex'];
$file = $_FILES['path-icon'];

if ($file['error'] !== UPLOAD_ERR_OK) {
  http_response_code(500);
  echo json_encode(['message' => 'File upload error: ' . $file['error']]);
  exit;
}

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/tabs/' . $productId . '/';
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
$newFileName = uniqid('icon_') . '.' . $fileExtension;
$uploadFilePath = $uploadDir . $newFileName;

try {
  $pdo->beginTransaction();

  file_put_contents($log_file, "\n📊 [PHP] Читаем tabs_data из БД для productId: $productId\n", FILE_APPEND);
  
  $stmt = $pdo->prepare("SELECT tabs_data FROM TabsAdditionalProductsData WHERE product_id = :product_id");
  $stmt->execute([':product_id' => $productId]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  $tabs = $result ? json_decode($result['tabs_data'], true) : [];
  
  file_put_contents($log_file, "📥 [PHP] tabs_data из БД:\n", FILE_APPEND);
  file_put_contents($log_file, json_encode($tabs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);

  if (!isset($tabs[$tabIndex])) {
    $tabs[$tabIndex] = ['title' => 'Новая вкладка', 'content' => []];
  }
  if (!isset($tabs[$tabIndex]['content'][$itemIndex])) {
    $tabs[$tabIndex]['content'][$itemIndex] = ['title' => 'Новый элемент', 'description' => '', 'path-icon' => ''];
  }
  
  if (!empty($tabs[$tabIndex]['content'][$itemIndex]['path-icon'])) {
      $oldIconUrl = $tabs[$tabIndex]['content'][$itemIndex]['path-icon'];
      $oldIconPath = $_SERVER['DOCUMENT_ROOT'] . parse_url($oldIconUrl, PHP_URL_PATH);
      if (file_exists($oldIconPath)) {
          unlink($oldIconPath);
      }
  }

  if (!move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
    throw new Exception("Failed to move uploaded file.");
  }

  $newIconPath = '/server/uploads/tabs/' . $productId . '/' . $newFileName;
  file_put_contents($log_file, "\n✅ [PHP] Файл сохранен: $newIconPath\n", FILE_APPEND);
  
  $tabs[$tabIndex]['content'][$itemIndex]['path-icon'] = $newIconPath;
  
  file_put_contents($log_file, "📝 [PHP] Обновляем tabs[$tabIndex]['content'][$itemIndex]['path-icon'] = $newIconPath\n", FILE_APPEND);
  file_put_contents($log_file, "\n📊 [PHP] tabs ПЕРЕД сохранением в БД:\n", FILE_APPEND);
  file_put_contents($log_file, json_encode($tabs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);

  $updateStmt = $pdo->prepare("
        INSERT INTO TabsAdditionalProductsData (product_id, tabs_data) 
        VALUES (:product_id, :tabs_data)
        ON DUPLICATE KEY UPDATE tabs_data = :tabs_data
    ");
  $updateStmt->execute([
    ':product_id' => $productId,
    ':tabs_data' => json_encode($tabs)
  ]);

  $pdo->commit();
  
  file_put_contents($log_file, "✅ [PHP] tabs_data СОХРАНЕН В БД\n", FILE_APPEND);
  file_put_contents($log_file, "✅ [PHP] Возвращаем клиенту: " . json_encode(['filePath' => $newIconPath]) . "\n", FILE_APPEND);
  file_put_contents($log_file, "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n", FILE_APPEND);

  http_response_code(200);
  echo json_encode(['filePath' => $newIconPath]);

} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  if (file_exists($uploadFilePath)) {
    unlink($uploadFilePath);
  }
  http_response_code(500);
  echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
}
