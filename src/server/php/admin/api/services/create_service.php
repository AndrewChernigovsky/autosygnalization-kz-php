<?php
namespace API\SERVICES\ADMIN;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\Database;
use Exception;
use HELPERS\SlugHelper;

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['message' => 'Method Not Allowed']);
  exit;
}

$dbConnection = Database::getConnection();
$pdo = $dbConnection->getPdo();

$data = json_decode(file_get_contents("php://input"), true);

if (json_last_error() !== JSON_ERROR_NONE) {
  http_response_code(400);
  echo json_encode(['message' => 'Invalid JSON input.']);
  exit;
}

try {
  $pdo->beginTransaction();

  // Generate unique slug for the 'type' field
  $baseSlug = (new SlugHelper)->generate($data['name']);
  $type = $baseSlug;
  $counter = 1;
  while (true) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Services WHERE type = :type");
    $stmt->execute([':type' => $type]);
    if ($stmt->fetchColumn() == 0) {
      break;
    }
    $type = $baseSlug . '-' . $counter++;
  }

  $stmt = $pdo->prepare(
    "INSERT INTO Services (name, description, type, image_src, image_alt, href, services, cost, currency)
         VALUES (:name, :description, :type, :image_src, :image_alt, :href, :services, :cost, :currency)"
  );

  $stmt->execute([
    ':name' => $data['name'],
    ':description' => $data['description'],
    ':type' => $type,
    ':image_src' => $data['image']['src'] ?? null,
    ':image_alt' => $data['image']['description'] ?? '',
    ':href' => '/service?service=' . $type,
    ':services' => is_array($data['services']) ? json_encode($data['services'], JSON_UNESCAPED_UNICODE) : $data['services'],
    ':cost' => $data['cost'],
    ':currency' => $data['currency'] ?? 'KZT'
  ]);

  $newId = $pdo->lastInsertId();
  $pdo->commit();

  // Fetch the newly created service to return it
  $selectStmt = $pdo->prepare("SELECT * FROM Services WHERE id = :id");
  $selectStmt->execute([':id' => $newId]);
  $newService = $selectStmt->fetch(\PDO::FETCH_ASSOC);

  // Re-structure the image data
  if ($newService) {
    $newService['image'] = [
      'src' => $newService['image_src'],
      'description' => $newService['image_alt']
    ];
    unset($newService['image_src'], $newService['image_alt']);
  }

  http_response_code(201);
  echo json_encode($newService);

} catch (Exception $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  http_response_code(500);
  echo json_encode(['message' => 'Failed to create service: ' . $e->getMessage()]);
}