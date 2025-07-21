<?php

namespace API\SERVICES\ADMIN;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\Database;
use Exception;

// CORS headers are handled by .htaccess in /src

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
  http_response_code(405);
  echo json_encode(['message' => 'Method Not Allowed']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

if (!isset($_POST['serviceId'], $_FILES['image'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Missing required parameters.']);
  exit;
}

$serviceId = $_POST['serviceId'];
$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
  http_response_code(500);
  echo json_encode(['message' => 'File upload error: ' . $file['error']]);
  exit;
}

$uploadDir = __DIR__ . '/../../../../../server/uploads/services/';
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
$newFileName = 'service-' . $serviceId . '-' . time() . '.' . $fileExtension;
$uploadFilePath = $uploadDir . $newFileName;
$newImagePath = '/server/uploads/services/' . $newFileName;

$dbConnection = Database::getConnection();
$pdo = $dbConnection->getPdo();

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("SELECT image_src FROM Services WHERE id = :id");
  $stmt->execute([':id' => $serviceId]);
  $oldImagePath = $stmt->fetchColumn();

  if ($oldImagePath) {
    $oldFileFullPath = __DIR__ . '/../../../../../' . ltrim($oldImagePath, '/');
    if (file_exists($oldFileFullPath)) {
      unlink($oldFileFullPath);
    }
  }

  if (!move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
    throw new Exception("Failed to move uploaded file.");
  }

  $updateStmt = $pdo->prepare("UPDATE Services SET image_src = :image_src WHERE id = :id");
  $updateStmt->execute([':image_src' => $newImagePath, ':id' => $serviceId]);

  $pdo->commit();

  http_response_code(200);
  echo json_encode(['filePath' => $newImagePath]);

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