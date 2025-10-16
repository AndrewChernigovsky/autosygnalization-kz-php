<?php

namespace API\ADMIN;
function log_message($message)
{
    // Пытаемся писать рядом со скриптом
    $baseDir = __DIR__;
    $log_file = $baseDir . '/docs-debug.log';
    $timestamp = date('Y-m-d H:i:s');
    if (!is_writable($baseDir)) {
        // Фоллбек в системный TMP, если каталог недоступен для записи
        $fallback = sys_get_temp_dir() . '/docs-debug.log';
        @file_put_contents($fallback, "[$timestamp] " . $message . "\n", FILE_APPEND);
        return;
    }
    file_put_contents($log_file, "[$timestamp] " . $message . "\n", FILE_APPEND);
}

// Корректный заголовок CORS: только один Origin
$allowed_origins = [
    'http://localhost:5173',
    'https://starline-service.kz',
    'https://www.starline-service.kz'
];
$request_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($request_origin, $allowed_origins, true)) {
    header("Access-Control-Allow-Origin: $request_origin");
    header('Vary: Origin');
} else {
    // Фоллбек на прод домен
    header("Access-Control-Allow-Origin: https://starline-service.kz");
}
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../vendor/autoload.php';
use DATABASE\DataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    log_message("OPTIONS запрос");
    http_response_code(200);
    exit(0);
}

class DocsAPI extends DataBase {
    protected $pdo;

    public function __construct()
    {
        $db = DataBase::getInstance();
        $this->pdo = $db->getPdo();
    }

    public function getAllDocs()
    {   
        try {
            $query = "SELECT * FROM docs";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $docs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->success($docs);
        } catch (Exception $e) {
            log_message("Критическая ошибка API 'Документы': " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Внутренняя ошибка сервера']);
            return $this->error($e->getMessage());
        }
    }

    public function updateDoc($data, $file = null)
    {
        if (!isset($data['id'])) {
            return $this->error("Отсутствует ID для обновления", 400);
        }
        
        $id = $data['id'];
        $key = $data['key'] ?? '';
        
        try {
            log_message("updateDoc: id=$id, key='$key', DOCUMENT_ROOT='" . ($_SERVER['DOCUMENT_ROOT'] ?? '') . "'");
            // Обновляем ключ если он передан
            if (!empty($key)) {
                $query = "UPDATE docs SET `key` = :key WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([':key' => $key, ':id' => $id]);
            }
            
            // Если передан файл, обрабатываем его
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                log_message("File incoming: name='{$file['name']}', type='{$file['type']}', size={$file['size']}, tmp='{$file['tmp_name']}'");
                if (!is_uploaded_file($file['tmp_name'])) {
                    log_message("is_uploaded_file=false for tmp='{$file['tmp_name']}'");
                }
                // Получаем старый путь
                $query = "SELECT path FROM docs WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([':id' => $id]);
                $old_path = $stmt->fetchColumn();
                
                // Удаляем старый файл если он существует
                if ($old_path) {
                    $old_abs = ($_SERVER['DOCUMENT_ROOT'] ?? '') . $old_path;
                    if (file_exists($old_abs)) {
                        if (!@unlink($old_abs)) {
                            log_message("Failed to unlink old file: $old_abs");
                        } else {
                            log_message("Old file removed: $old_abs");
                        }
                    } else {
                        log_message("Old file not found to unlink: $old_abs");
                    }
                }
                
                // Загружаем новый файл
                $upload_dir = rtrim(($_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
                $upload_dir .= '/client/docs/';
                if (!is_dir($upload_dir)) {
                    if (!mkdir($upload_dir, 0777, true)) {
                        log_message("mkdir failed: $upload_dir");
                        return $this->error("Не удалось создать директорию загрузки", 500);
                    }
                }
                $dir_perms = substr(sprintf('%o', @fileperms($upload_dir)), -4);
                $dir_writable = is_writable($upload_dir) ? 'true' : 'false';
                log_message("Upload dir: '$upload_dir', perms=$dir_perms, writable=$dir_writable");
                
                $new_filename = $key . '.txt';
                $upload_file = $upload_dir . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $upload_file)) {
                    $relative_path = '/client/docs/' . $new_filename;
                    $query = "UPDATE docs SET path = :path WHERE id = :id";
                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute([':path' => $relative_path, ':id' => $id]);
                    log_message("File moved to: $upload_file, db path set to: $relative_path");
                } else {
                    $err = error_get_last();
                    log_message("move_uploaded_file failed to '$upload_file' error=" . json_encode($err));
                    return $this->error("Не удалось сохранить загруженный файл", 500);
                }
            }
            
            return $this->success(['id' => $id, 'message' => 'Документ обновлен']);
            
        } catch (Exception $e) {
            log_message("Критическая ошибка API 'Документы': " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Внутренняя ошибка сервера']);
            return $this->error($e->getMessage());
        }
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                echo $this->getAllDocs();
                break;
            case 'POST':
                // Проверяем, есть ли файлы
                if (!empty($_FILES)) {
                    // Обрабатываем FormData
                    $data = $_POST;
                    $file = isset($_FILES['file']) ? $_FILES['file'] : null;
                    echo $this->updateDoc($data, $file);
                } else {
                    // Обрабатываем JSON (для обратной совместимости)
                    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
                    if ($contentType === "application/json") {
                        $content = trim(file_get_contents("php://input"));
                        $decoded = json_decode($content, true);
                        if (is_array($decoded)) {
                            if (isset($decoded['action']) && $decoded['action'] === 'update') {
                                echo $this->updateDoc($decoded, $_FILES['file'] ?? null);
                            } else {
                                echo $this->error("Неверный JSON", 400);
                            }
                        } else {
                            echo $this->error("Неверный JSON", 400);
                        }
                    } else {
                        echo $this->error("Неверный тип контента", 400);
                    }
                }
                break;
            default:
                echo $this->error("Неверный метод", 405);
                break;
        }
    }

    private function success($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        return json_encode(['success' => true, 'data' => $data]);
    }
  
    private function error($message, $statusCode = 400)
    {
        http_response_code($statusCode);
        return json_encode(['success' => false, 'error' => $message]);
    }
}

$api = new DocsAPI();
$api->handleRequest();