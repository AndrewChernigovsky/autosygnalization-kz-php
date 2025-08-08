<?php
namespace API\SERVICES\ADMIN;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\DataBase;
use Exception;

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

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Service ID not provided.']);
    exit;
}

$serviceId = $data['id'];
$imagePath = $data['image_path'] ?? null;

$dbConnection = DataBase::getConnection();
$pdo = $dbConnection->getPdo();

try {
    $pdo->beginTransaction();

    // Delete the image file if path is provided
    if ($imagePath) {
        $fullPath = realpath(__DIR__ . '/../../../../../' . ltrim($imagePath, '/'));
        $baseDir = realpath(__DIR__ . '/../../../../../server/uploads');

        if ($fullPath && strpos($fullPath, $baseDir) === 0 && file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    // Delete the service from the database
    $stmt = $pdo->prepare("DELETE FROM Services WHERE id = :id");
    $stmt->execute([':id' => $serviceId]);

    $pdo->commit();

    http_response_code(200);
    echo json_encode(['message' => 'Service deleted successfully.']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['message' => 'Failed to delete service: ' . $e->getMessage()]);
} 