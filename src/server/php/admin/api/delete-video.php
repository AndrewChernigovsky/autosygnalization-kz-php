<?php
namespace API\ADMIN;

// Подключение автозагрузчика Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Подключение конфигурации базы данных
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

use DATABASE\InitDataBase;
use Exception;

// Функция для логирования
function logMessage($message) {
    $logFile = __DIR__ . '/delete-log.txt';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    logMessage('Метод запроса не DELETE: ' . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

logMessage('Запрос DELETE получен');

// Получение ID записи из параметров URL
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    logMessage('Некорректный ID записи: ' . $id);
    http_response_code(400);
    echo json_encode(['error' => 'Invalid video ID']);
    exit;
}

logMessage('ID записи для удаления: ' . $id);

// Удаление записи и файла
try {
    logMessage('Попытка подключения к базе данных');
    $db = new InitDataBase();
    logMessage('Объект InitDataBase создан успешно');
    
    // Сначала получаем информацию о файле
    $stmt = $db->prepare("SELECT video_filename, video_path FROM Videos_intro_slider WHERE id = ?");
    $stmt->execute([$id]);
    $video = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    if (!$video) {
        logMessage('Запись с ID ' . $id . ' не найдена в базе данных');
        http_response_code(404);
        echo json_encode(['error' => 'Video not found']);
        exit;
    }
    
    logMessage('Найдена запись: ' . json_encode($video));
    
    // Удаляем запись из базы данных
    $deleteStmt = $db->prepare("DELETE FROM Videos_intro_slider WHERE id = ?");
    $deleteStmt->execute([$id]);
    
    if ($deleteStmt->rowCount() > 0) {
        logMessage('Запись удалена из базы данных');
        
        // Удаляем файл с сервера
        $baseUploadDir = __DIR__ . '/../../../uploads/';
        $filePath = $baseUploadDir . 'slider-intro/' . $video['video_filename'];
        
        logMessage('Путь к файлу для удаления: ' . $filePath);
        
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                logMessage('Файл успешно удален: ' . $filePath);
            } else {
                logMessage('Не удалось удалить файл: ' . $filePath);
            }
        } else {
            logMessage('Файл не найден: ' . $filePath);
        }
        
        // Успешное удаление
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Video deleted successfully',
            'deleted_id' => $id
        ]);
        logMessage('Ответ отправлен клиенту');
        
    } else {
        logMessage('Не удалось удалить запись из базы данных');
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete video from database']);
        exit;
    }
    
} catch (Exception $e) {
    logMessage('Исключение при удалении видео: ' . $e->getMessage());
    logMessage('Трассировка стека: ' . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode(['error' => 'Delete error: ' . $e->getMessage()]);
    exit;
}
?> 