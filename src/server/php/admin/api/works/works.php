<?php

namespace API\ADMIN;

// ВРЕМЕННО: Для отладки включаем отображение всех ошибок
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');


require_once __DIR__ . '/../../../../vendor/autoload.php';


use DATABASE\DataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

class WorksAPI extends DataBase {
    protected $pdo;

    public function __construct() {
        $this->pdo = DataBase::getInstance()->getPdo();
    }

    // Приватные функции

    private function success($data, $statusCode = 200) {
        http_response_code($statusCode);
        return json_encode(['success' => true, 'data' => $data]);
    }

    private function error($message, $statusCode = 400) {
        http_response_code($statusCode);
        return json_encode(['success' => false, 'error' => $message]);
    }

    private function checkComingItemString($item) {
        if (empty($item) || !is_string($item) || strlen($item) > 255) {
            return null;
        }
        return $item;
    }

    private function checkComingItemPosition($item) {
        if (empty($item) || !is_numeric($item)) {
            return null;
        }
        return $item;
    }

    private function checkComingItemImage(array $file) {
        $requiredKeys = ['name', 'type', 'tmp_name', 'error', 'size'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $file)) {
                return false;
            }
        }
    
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
    
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
    
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }
    
        if (!is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/works/images/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('works_', true) . '.' . $file_extension;
        $upload_file = $upload_dir . $new_filename;
        
        if (move_uploaded_file($file['tmp_name'], $upload_file)) {
            return '/server/uploads/works/images/' . $new_filename;
        }
        return false;
    }

    private function deleteImage($image_path) {
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    private function validateData($data, $files) {
        $title = $this->checkComingItemString($data['title']);
        $link = $this->checkComingItemString($data['link']);
        $image_path = $this->checkComingItemImage($files['image']);
        $position = $this->checkComingItemPosition($data['position']);

        $data['title'] = $title;
        $data['link'] = $link;
        $data['image_path'] = $image_path;
        $data['position'] = $position;

        if (!$title || !$link || !$image_path || !$position) {
            return false;
        }
        return $data;
    }

    private function executeGet($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function executePost($query, $params) {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }

    private function getWorkById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Works WHERE work_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0] ?? null;
    }

    private function prepareUpdateData($old_data, $incoming_data, $files = null) {
        $image_path = $old_data['image_path'];
        if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
            $new_image_path = $this->checkComingItemImage($files['image']);
            if ($new_image_path === false) {
                return false; 
            }
            if ($old_data['image_path']) {
                $this->deleteImage($_SERVER['DOCUMENT_ROOT'] . $old_data['image_path']);
            }
            $image_path = $new_image_path;
        }

        if (isset($incoming_data['title']) && $old_data['title'] != $incoming_data['title'] && $this->checkComingItemString($incoming_data['title'])) {
            $old_data['title'] = $incoming_data['title'];
        }
        if (isset($incoming_data['link']) && $old_data['link'] != $incoming_data['link'] && $this->checkComingItemString($incoming_data['link'])) {
            $old_data['link'] = $incoming_data['link'];
        }
        if (isset($incoming_data['position']) && $old_data['position'] != $incoming_data['position'] && $this->checkComingItemPosition($incoming_data['position'])) {
            $old_data['position'] = $incoming_data['position'];
        }

        $old_data['image_path'] = $image_path;
        
        return $old_data;
    }

    // Публичные функции

    public function handleRequest() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];

            switch ($method) {
                case 'GET':
                    $works = $this->getAllWorks();
                    $services = $this->getAllServices();
                    echo $this->success(['works' => $works, 'services' => $services]);
                    break;

                case 'POST':
                    $contentType = trim($_SERVER["CONTENT_TYPE"] ?? '');
                    if (strpos($contentType, 'application/json') !== false) {
                        $input = json_decode(file_get_contents('php://input'), true);
                        if (isset($input['action']) && $input['action'] === 'update_positions') {
                            $this->updatePositions($input['items'] ?? []);
                        } else {
                            echo $this->error("Неверный JSON-запрос", 400);
                        }
                    } else {
                        if (isset($_POST['work_id'])) {
                            $this->updateWork($_POST, $_FILES);
                        } else {
                            $this->createWork($_POST, $_FILES);
                        }
                    }
                    break;

                case 'DELETE':
                    $id = $_GET['id'] ?? null;
                    $this->deleteWork($id);
                    break;

                default:
                    echo $this->error("Метод не поддерживается", 405);
                    break;
            }
        } catch (\Exception $e) {
            error_log("Критическая ошибка API 'Работы': " . $e->getMessage());
            echo $this->error('Внутренняя ошибка сервера', 500);
        }
    }

    public function getAllWorks() {
        try {
            $query = "SELECT * FROM Works ORDER BY position ASC";
            return $this->executeGet($query);
        } catch (\Exception $e) {
            error_log("Ошибка получения данных из таблицы WORKS в API: " . $e->getMessage());
            return [['title' => 'none', 'link' => '/', 'image_path' => '/', 'position' => 0]];
        }
    }

    public function getAllServices() {
        try {
            $query = "SELECT name AS title, href AS link FROM Services";
            return $this->executeGet($query);
        } catch (\Exception $e) {
            error_log("Ошибка получения данных из таблицы SERVICES в API: " . $e->getMessage());
            return [['title' => 'none', 'link' => '/']];
        }
    }


    public function createWork($data, $files) {
        try {
            $validated_data = $this->validateData($data, $files);

            if (!$validated_data) {
                echo $this->error('Некорректные данные были переданы в API при создании работы');
                return;
            }

            $query = "INSERT INTO Works (title, link, image_path, position) VALUES (:title, :link, :image_path, :position)";
            $params = [
                ':title' => $validated_data['title'],
                ':link' => $validated_data['link'],
                ':image_path' => $validated_data['image_path'],
                ':position' => $validated_data['position']
            ];
            $is_success = $this->executePost($query, $params);

            if($is_success) {
                echo $this->success('Работа успешно создана');
            } else {
                echo $this->error('Ошибка записи в базу данных');
            }
        } catch (\Exception $e) {
            echo $this->error('Ошибка при создании работы: ' . $e->getMessage());
        }
    }

    public function updateWork($data, $files = null) {
        
        try {
            if (!isset($data['work_id']) || empty($data['work_id']) || !is_numeric($data['work_id'])) {
                echo $this->error('Отсутствует ID для обновления');
                return;
            }
            $current_work_data = $this->getWorkById($data['work_id']);
            if (!$current_work_data) {
                echo $this->error('Работа с таким ID не найдена');
                return;
            }
            
            $prepared_data = $this->prepareUpdateData($current_work_data, $data, $files);
            if ($prepared_data === false) {
                echo $this->error('Ошибка при загрузке изображения. Проверьте тип и размер файла.');
                return;
            }

            $query = "UPDATE Works SET title = :title, link = :link, image_path = :image_path, position = :position WHERE work_id = :id";
            $params = [
                ':id' => $prepared_data['work_id'],
                ':title' => $prepared_data['title'],
                ':link' => $prepared_data['link'],
                ':image_path' => $prepared_data['image_path'],
                ':position' => $prepared_data['position']
            ];
            $is_success = $this->executePost($query, $params);

            if ($is_success) {
                echo $this->success('Работа успешно обновлена');
            } else {
                echo $this->error('Ошибка при обновлении записи в базе данных');
            }
        } catch (\Exception $e) {
            echo $this->error('Ошибка при обновлении работы: ' . $e->getMessage());
        }
    }   

    public function deleteWork($id) {
        try {
            $work = $this->getWorkById($id);
            if (!$work) {
                echo $this->error('Работа с таким ID не найдена');
                return;
            }
            if ($work['image_path']) {
                $this->deleteImage($_SERVER['DOCUMENT_ROOT'] . $work['image_path']);
            }
            $query = "DELETE FROM Works WHERE work_id = :id";
            $params = [':id' => $id];
            $is_success = $this->executePost($query, $params);
            if ($is_success) {
                echo $this->success('Работа успешно удалена');
            } else {
                echo $this->error('Ошибка при удалении работы');
            }
        } catch (\Exception $e) {
            echo $this->error('Ошибка при удалении работы: ' . $e->getMessage());
        }
    }

    public function updatePositions($items) {
        if (empty($items) || !is_array($items)) {
            echo $this->error("Нет данных для обновления позиций", 400);
            return;
        }
        try {
            $this->pdo->beginTransaction();
            $query = "UPDATE Works SET position = :position WHERE work_id = :id";
            $stmt = $this->pdo->prepare($query);
            foreach ($items as $item) {
                if (isset($item['work_id']) && isset($item['position'])) {
                    $stmt->execute([
                        ':id' => $item['work_id'],
                        ':position' => $item['position'],
                    ]);
                }
            }
            $this->pdo->commit();
            echo $this->success(['status' => 'Позиции работ успешно обновлены']);
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            error_log("Ошибка обновления позиций: " . $e->getMessage());
            echo $this->error("Ошибка обновления позиций", 500);
        }   
    }
}

$api = new WorksAPI();
$api->handleRequest();