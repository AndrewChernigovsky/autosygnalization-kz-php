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

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Slide ID is required for poster deletion']);
    exit;
}

try {
    $db = new InitDataBase();

    $stmt = $db->prepare("SELECT poster_path FROM Videos_intro_slider WHERE id = ?");
    $stmt->execute([$id]);
    $slide = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($slide && !empty($slide['poster_path'])) {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $slide['poster_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stmt = $db->prepare("UPDATE Videos_intro_slider SET poster_path = '' WHERE id = ?");
    $stmt->execute([$id]);

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Poster deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 