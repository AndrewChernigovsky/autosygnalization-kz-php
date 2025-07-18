<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/config.php';

use DATABASE\Database;

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

if (!isset($data['productId'], $data['imageIndex'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product ID or image index not provided.']);
  exit;
}

$productId = $data['productId'];
$imageIndex = $data['imageIndex'];

try {
  // Получаем текущую галерею
  $stmt = $db->prepare("SELECT gallery FROM Products WHERE id = :id");
  $stmt->bindParam(':id', $productId);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
    http_response_code(404);
    echo json_encode(['message' => 'Product not found.']);
    exit;
  }

  $gallery = json_decode($result['gallery'], true);

  if (isset($gallery[$imageIndex])) {
    $imageUrlToDelete = $gallery[$imageIndex];

    // Удаляем файл с сервера
    $filePath = $_SERVER['DOCUMENT_ROOT'] . parse_url($imageUrlToDelete, PHP_URL_PATH);
    if (file_exists($filePath)) {
      unlink($filePath);
    }

    // Удаляем из массива
    unset($gallery[$imageIndex]);
    $newGallery = array_values($gallery); // Re-index array

    // Обновляем галерею в БД
    $updateStmt = $db->prepare("UPDATE Products SET gallery = :gallery WHERE id = :id");
    $galleryJson = json_encode($newGallery);
    $updateStmt->bindParam(':gallery', $galleryJson);
    $updateStmt->bindParam(':id', $productId);

    if ($updateStmt->execute()) {
      echo json_encode(['message' => 'Image deleted successfully.', 'gallery' => $newGallery]);
    } else {
      throw new Exception("Failed to update database.");
    }
  } else {
    http_response_code(404);
    echo json_encode(['message' => 'Image not found in gallery.']);
  }

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
}