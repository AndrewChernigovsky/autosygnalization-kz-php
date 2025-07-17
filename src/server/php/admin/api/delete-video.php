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
    echo json_encode(['error' => 'Slide ID is required for video deletion']);
    exit;
}

try {
    $db = new InitDataBase();

    $stmt = $db->prepare("SELECT video_path, video_path_mob FROM Videos_intro_slider WHERE id = ?");
    $stmt->execute([$id]);
    $slide = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($slide) {
        if (!empty($slide['video_path'])) {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $slide['video_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        if (!empty($slide['video_path_mob'])) {
            $mobileFilePath = $_SERVER['DOCUMENT_ROOT'] . $slide['video_path_mob'];
            if (file_exists($mobileFilePath)) {
                unlink($mobileFilePath);
            }
        }
    }

    $stmt = $db->prepare("UPDATE Videos_intro_slider SET video_path = '', video_path_mob = '', video_filename = '' WHERE id = ?");
    $stmt->execute([$id]);

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Video files deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 