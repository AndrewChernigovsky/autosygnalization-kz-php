<?php
namespace API\ADMIN;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';

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
    echo json_encode(['error' => 'Slide ID is required']);
    exit;
}

try {
    $db = new InitDataBase();

    // First, get the video path to delete the file
    $stmt = $db->prepare("SELECT video_path FROM Videos_intro_slider WHERE id = ?");
    $stmt->execute([$id]);
    $slide = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($slide && !empty($slide['video_path'])) {
        // Warning: This assumes the path is relative to the document root.
        // Adjust if the path stored is different.
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $slide['video_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Then, delete the record from the database
    $stmt = $db->prepare("DELETE FROM Videos_intro_slider WHERE id = ?");
    $stmt->execute([$id]);

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Slide deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 