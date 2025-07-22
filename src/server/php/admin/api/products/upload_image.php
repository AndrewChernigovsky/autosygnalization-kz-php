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

if (!isset($_POST['productId'], $_FILES['image'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Product ID or image not provided.']);
  exit;
}

$productId = $_POST['productId'];
$imageFile = $_FILES['image'];
$imageIndex = $_POST['imageIndex'] ?? null;

// Проверка на ошибки загрузки
if ($imageFile['error'] !== UPLOAD_ERR_OK) {
  http_response_code(500);
  echo json_encode(['message' => 'Error uploading file.']);
  exit;
}

$isNewProduct = strpos($productId, 'new_') === 0;

// Создаем уникальное имя файла
$extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
$filename = Uuid::uuid4()->toString() . '.' . $extension;
$uploadDir = '/server/uploads/products/gallery/' . $productId . '/';
$destinationDirectory = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

// Создаем директорию, если она не существует
if (!is_dir($destinationDirectory)) {
  if (!mkdir($destinationDirectory, 0777, true)) {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to create upload directory.']);
    exit;
  }
}

$uploadPath = $destinationDirectory . $filename;
$uploadUrl = $uploadDir . $filename;

if (move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
  if ($isNewProduct) {
    // Для нового продукта просто возвращаем URL, не трогая БД
    // Галерею на клиенте нужно будет обновить и отправить при создании продукта
    $tempGallery = json_decode($_POST['gallery'] ?? '[]', true);
    $tempGallery[] = $uploadUrl;
    echo json_encode(['gallery' => $tempGallery]);
    exit;
  }

  try {
    // Получаем текущую галерею
    $stmt = $db->prepare("SELECT gallery FROM Products WHERE id = :id");
    $stmt->bindParam(':id', $productId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $gallery = $result ? json_decode($result['gallery'], true) : [];

    if ($imageIndex !== null && isset($gallery[$imageIndex])) {
      // Заменяем изображение
      $oldImageUrl = $gallery[$imageIndex];
      $gallery[$imageIndex] = $uploadUrl;
      // Удаляем старый файл
      $oldFilePath = $_SERVER['DOCUMENT_ROOT'] . parse_url($oldImageUrl, PHP_URL_PATH);
      if (file_exists($oldFilePath)) {
        unlink($oldFilePath);
      }
    } else {
      // Добавляем новое изображение
      $gallery[] = $uploadUrl;
    }

    // Обновляем галерею в БД
    $updateStmt = $db->prepare("UPDATE Products SET gallery = :gallery WHERE id = :id");
    $galleryJson = json_encode(array_values($gallery)); // Re-index array
    $updateStmt->bindParam(':gallery', $galleryJson);
    $updateStmt->bindParam(':id', $productId);

    if ($updateStmt->execute()) {
      echo json_encode(['newImageUrl' => $uploadUrl, 'gallery' => $gallery]);
    } else {
      throw new Exception("Failed to update database.");
    }

  } catch (Exception $e) {
    http_response_code(500);
    // Удаляем загруженный файл, если произошла ошибка БД
    unlink($uploadPath);
    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
  }
} else {
  http_response_code(500);
  echo json_encode(['message' => 'Failed to move uploaded file.']);
}