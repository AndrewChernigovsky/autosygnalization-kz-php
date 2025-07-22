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

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product ID not provided.']);
  exit;
}

$productId = $data['id'];

try {
  // 1. Получить пути к изображениям из галереи
  $stmt = $db->prepare("SELECT gallery FROM Products WHERE id = :id");
  $stmt->bindParam(':id', $productId);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result && !empty($result['gallery'])) {
    $gallery = json_decode($result['gallery'], true);
    foreach ($gallery as $imageUrl) {
      // Преобразуем URL в путь к файлу
      // Предполагается, что DOCUMENT_ROOT содержит путь до корня сайта
      $filePath = $_SERVER['DOCUMENT_ROOT'] . parse_url($imageUrl, PHP_URL_PATH);
      if (file_exists($filePath)) {
        unlink($filePath);
      }
    }
  }

  // 2. Удалить товар из базы данных
  $stmt = $db->prepare("DELETE FROM Products WHERE id = :id");
  $stmt->bindParam(':id', $productId);

  if ($stmt->execute()) {
    if ($stmt->rowCount() > 0) {
      echo json_encode(['message' => 'Product deleted successfully.']);
    } else {
      http_response_code(404);
      echo json_encode(['message' => 'Product not found.']);
    }
  } else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to delete product.']);
  }
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
