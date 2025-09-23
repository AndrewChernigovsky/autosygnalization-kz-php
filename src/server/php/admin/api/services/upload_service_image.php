<?php

namespace API\SERVICES\ADMIN;

// Set CORS headers
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');


// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['message' => 'Method Not Allowed']);
  exit;
}

if (!isset($_FILES['image'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Image not provided.']);
  exit;
}

$file = $_FILES['image'];
$serviceId = $_POST['id'] ?? 'service'; // Fallback if serviceId is not provided

if ($file['error'] !== UPLOAD_ERR_OK) {
  http_response_code(500);
  echo json_encode(['message' => 'File upload error: ' . $file['error']]);
  exit;
}

$uploadDir = __DIR__ . '/../../../../../server/uploads/services/';
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

$fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$originalFileName = pathinfo($file['name'], PATHINFO_FILENAME);
$sanitizedFileName = preg_replace('/[^a-zA-Z0-9_-]/', '', $originalFileName);
$newFileName = 'service-' . $sanitizedFileName . '-' . time() . '.' . $fileExtension;
$uploadFilePath = $uploadDir . $newFileName;
$newImagePath = '/server/uploads/services/' . $newFileName;

error_log(print_r($file, true) . 'FILEFEFE');

try {
  if (!move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
    throw new Exception("Failed to move uploaded file.");
  }

  http_response_code(200);
  echo json_encode(['id' => $serviceId, 'path' => $newImagePath, 'filename' => $newFileName]);
} catch (Exception $e) {
  if (file_exists($uploadFilePath)) {
    unlink($uploadFilePath);
  }
  http_response_code(500);
  echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
}