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

use DATABASE\Database;
use Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['message' => 'Method Not Allowed']);
  exit;
}

$dbConnection = Database::getConnection();
$pdo = $dbConnection->getPdo();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  http_response_code(400);
  echo json_encode(['message' => 'Service ID not provided.']);
  exit;
}

// Log incoming data for debugging
error_log(print_r($data, true));

$serviceId = $data['id'];
$services_data = $data['services'];

// Ensure that the services data is a string (JSON encode if it's an array)
if (is_array($services_data)) {
  $services_data = json_encode($services_data, JSON_UNESCAPED_UNICODE);
}

// Extract image source
$image_src = null;
if (isset($data['image']) && is_array($data['image']) && isset($data['image']['src'])) {
  $image_src = $data['image']['src'];
}

// Handle old image deletion
$oldImagePath = $data['old_image_path'] ?? null;
if ($oldImagePath && $oldImagePath !== $image_src) {
  $oldFileFullPath = realpath(__DIR__ . '/../../../../../' . ltrim($oldImagePath, '/'));
  $baseDir = realpath(__DIR__ . '/../../../../../server/uploads');

  // Security check to prevent deleting files outside the uploads directory
  if ($oldFileFullPath && strpos($oldFileFullPath, $baseDir) === 0 && file_exists($oldFileFullPath)) {
    unlink($oldFileFullPath);
  }
}


try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("UPDATE Services SET
        name = :name,
        description = :description,
        image_src = :image_src,
        services = :services,
        cost = :cost
        WHERE id = :id");

  $stmt->execute([
    ':id' => $serviceId,
    ':name' => $data['name'],
    ':description' => $data['description'],
    ':image_src' => $image_src,
    ':services' => $services_data,
    ':cost' => $data['cost']
  ]);

  $pdo->commit();

  http_response_code(200);
  echo json_encode(['message' => 'Service updated successfully.']);
} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Failed to update service: ' . $e->getMessage()]);
}