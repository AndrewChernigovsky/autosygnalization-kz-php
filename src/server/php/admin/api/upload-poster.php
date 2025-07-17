<?php
namespace API\ADMIN;

require_once __DIR__ . '/../../../vendor/autoload.php';

header('Content-Type: application/json');

use DATABASE\InitDataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method Not Allowed']);
  exit;
}

if (!isset($_FILES['poster'])) {
  http_response_code(400);
  echo json_encode(['error' => 'No poster file uploaded']);
  exit;
}

$file = $_FILES['poster'];
$slideId = $_POST['slide_id'] ?? null;

if (!$slideId) {
  http_response_code(400);
  echo json_encode(['error' => 'Slide ID is required']);
  exit;
}

if ($file['error'] !== UPLOAD_ERR_OK) {
  http_response_code(400);
  echo json_encode(['error' => 'File upload error: ' . $file['error']]);
  exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
if (!in_array($file['type'], $allowedTypes)) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, WEBP, and AVIF are allowed.']);
  exit;
}

$maxSize = 10 * 1024 * 1024; // 10MB
if ($file['size'] > $maxSize) {
  http_response_code(400);
  echo json_encode(['error' => 'File too large. Maximum size is 10MB.']);
  exit;
}

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/slider-intro/posters/';
if (!is_dir($uploadDir)) {
  if (!mkdir($uploadDir, 0755, true)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create upload directory']);
    exit;
  }
}

$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$uniqueName = 'poster-' . time() . '.' . $extension;
$uploadPath = $uploadDir . $uniqueName;

if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to save uploaded file']);
  exit;
}

try {
  $db = new InitDataBase();

  $stmt = $db->prepare("SELECT poster_path FROM Videos_intro_slider WHERE id = ?");
  $stmt->execute([$slideId]);
  $oldSlide = $stmt->fetch(\PDO::FETCH_ASSOC);

  if ($oldSlide && !empty($oldSlide['poster_path'])) {
    $oldFilePath = $_SERVER['DOCUMENT_ROOT'] . $oldSlide['poster_path'];
    if (file_exists($oldFilePath)) {
      unlink($oldFilePath);
    }
  }

  $posterPath = '/server/uploads/slider-intro/posters/' . $uniqueName;
  $stmt = $db->prepare("UPDATE Videos_intro_slider SET poster_path = ? WHERE id = ?");
  $stmt->execute([$posterPath, $slideId]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
  exit;
}

http_response_code(200);
echo json_encode([
  'success' => true,
  'message' => 'Poster uploaded successfully',
  'path' => $posterPath,
  'id' => (int) $slideId
]);
?> 