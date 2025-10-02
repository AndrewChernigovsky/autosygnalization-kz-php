<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/config.php';

use DATABASE\DataBase;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

$dbConnection = DataBase::getConnection();
$pdo = $dbConnection->getPdo();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product ID not provided.']);
  exit;
}

$productId = $data['id'];

// Helper function to delete directory recursively
function deleteDir($dirPath) {
    if (!is_dir($dirPath)) {
        return;
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    if (is_dir($dirPath)) {
        rmdir($dirPath);
    }
}


try {
  $pdo->beginTransaction();

  // 1. Delete gallery images and folder
  $galleryDir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/products/gallery/' . $productId;
  if (is_dir($galleryDir)) {
      deleteDir($galleryDir);
  }

  // 2. Delete tabs icons folder
  $tabsDir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/tabs/' . $productId;
  if (is_dir($tabsDir)) {
      deleteDir($tabsDir);
  }

  // 3. Delete from TabsAdditionalProductsData
  $stmtTabs = $pdo->prepare("DELETE FROM TabsAdditionalProductsData WHERE product_id = :id");
  $stmtTabs->bindParam(':id', $productId);
  $stmtTabs->execute();

  // 4. Delete product from Products table
  $stmt = $pdo->prepare("DELETE FROM Products WHERE id = :id");
  $stmt->bindParam(':id', $productId);

  if ($stmt->execute()) {
    if ($stmt->rowCount() > 0) {
      $pdo->commit();
      echo json_encode(['message' => 'Product and all related data deleted successfully.']);
    } else {
      $pdo->rollBack();
      http_response_code(404);
      echo json_encode(['message' => 'Product not found.']);
    }
  } else {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['message' => 'Failed to delete product.']);
  }
} catch (PDOException $e) {
  if ($pdo->inTransaction()) {
      $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
