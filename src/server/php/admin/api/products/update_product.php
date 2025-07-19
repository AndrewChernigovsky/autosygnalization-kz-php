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

try {
  $stmt = $db->prepare(
    "UPDATE Products SET 
            title = :title, 
            description = :description, 
            price = :price, 
            is_popular = :is_popular,
            gallery = :gallery,
            link = :link
        WHERE id = :id"
  );

  // Генерируем новую ссылку
  $link = "/product?category={$data['category_key']}&id={$data['id']}";

  // Привязываем параметры
  $stmt->bindParam(':id', $data['id']);
  $stmt->bindParam(':title', $data['title']);
  $stmt->bindParam(':description', $data['description']);
  $stmt->bindParam(':price', $data['price']);
  $stmt->bindParam(':link', $link);

  // Преобразуем boolean в integer для БД
  $is_popular = $data['is_popular'] ? 1 : 0;
  $stmt->bindParam(':is_popular', $is_popular);

  // Преобразуем массив галереи в JSON
  $galleryJson = json_encode($data['gallery']);
  $stmt->bindParam(':gallery', $galleryJson);

  if ($stmt->execute()) {
    echo json_encode(['message' => 'Product updated successfully.', 'link' => $link]);
  } else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to update product.']);
  }
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
