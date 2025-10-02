<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\DataBase;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

header('Content-Type: application/json');

$dbConnection = DataBase::getConnection();
$pdo = $dbConnection->getPdo();

// Basic validation
if (!isset($_POST['productId'], $_POST['tabIndex'], $_POST['itemIndex'], $_FILES['icon'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Missing required parameters.']);
  exit;
}

$productId = $_POST['productId'];
$tabIndex = (int) $_POST['tabIndex'];
$itemIndex = (int) $_POST['itemIndex'];
$file = $_FILES['icon'];

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
$newFileName = uniqid('icon_', true) . '.' . $fileExtension;
$uploadFilePath = $uploadDir . $newFileName;

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("SELECT tabs_data FROM TabsAdditionalProductsData WHERE product_id = :product_id");
  $stmt->execute([':product_id' => $productId]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  $tabs = $result ? json_decode($result['tabs_data'], true) : [];

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
  $tabs[$tabIndex]['content'][$itemIndex]['path-icon'] = $newIconPath;

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
