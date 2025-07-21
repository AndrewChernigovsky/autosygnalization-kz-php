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

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("UPDATE Services SET
        name = :name,
        description = :description,
        services = :services,
        cost = :cost
        WHERE id = :id");

  $stmt->execute([
    ':id' => $serviceId,
    ':name' => $data['name'],
    ':description' => $data['description'],
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