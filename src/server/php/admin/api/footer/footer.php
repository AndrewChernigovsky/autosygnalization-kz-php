<?php

namespace API\ADMIN;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\DataBase;
use Exception;

class FooterAPI extends DataBase
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DataBase::getInstance()->getPdo();
    }

    // --- Вспомогательные методы ---
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

    private function getLinkById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Footer WHERE footer_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    private function executePost($query, $params)
    {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }

    // --- Основные методы API ---

    public function handleRequest()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];

            switch ($method) {
                case 'GET':
                    $this->getAllLinks();
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
                        if (isset($_POST['footer_id'])) {
                            $this->updateLink($_POST);
                        } else {
                            $this->createLink($_POST);
                        }
                    }
                    break;

                case 'DELETE':
                    $id = $_GET['id'] ?? null;
                    $this->deleteLink($id);
                    break;

                default:
                    echo $this->error("Метод не поддерживается", 405);
                    break;
            }
        } catch (\Exception $e) {
            error_log("Критическая ошибка API 'Footer': " . $e->getMessage());
            echo $this->error('Внутренняя ошибка сервера', 500);
        }
    }

    public function getAllLinks()
    {
        $stmt = $this->pdo->query("SELECT * FROM Footer ORDER BY section, position ASC");
        $links = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        echo $this->success($links);
    }

    public function createLink($data)
    {
        $name = $data['name'] ?? null;
        $link = $data['link'] ?? null;
        $section = $data['section'] ?? null;
        $position = $data['position'] ?? 99;

        if (!$name || !$link || !$section) {
            echo $this->error('Не все поля (name, link, section) были переданы');
            return;
        }

        if (!in_array($section, ['shop', 'installation', 'client'])) {
            echo $this->error('Некорректный раздел');
            return;
        }

        try {
            $query = "INSERT INTO Footer (name, link, section, position, source_table) VALUES (:name, :link, :section, :position, 'custom')";
            $params = [
                ':name' => $name,
                ':link' => $link,
                ':section' => $section,
                ':position' => $position,
            ];
            $is_success = $this->executePost($query, $params);

            if ($is_success) {
                echo $this->success('Ссылка успешно создана');
            } else {
                echo $this->error('Ошибка при создании ссылки');
            }
        } catch (\Exception $e) {
            echo $this->error('Ошибка при создании ссылки: ' . $e->getMessage());
        }
    }

    public function updateLink($data)
    {
        $id = $data['footer_id'] ?? null;
        if (!$id) {
            echo $this->error('ID ссылки не указан');
            return;
        }

        $current_link = $this->getLinkById($id);
        if (!$current_link) {
            echo $this->error('Ссылка с таким ID не найдена');
            return;
        }
        
        // Поля для обновления
        $update_fields = [];
        $params = [':id' => $id];

        // Поле visible можно обновлять всегда
        if (isset($data['visible'])) {
            $update_fields[] = "visible = :visible";
            $params[':visible'] = ($data['visible'] === 'true' || $data['visible'] === '1') ? 1 : 0;
        }

        // Поле position можно обновлять всегда
        if (isset($data['position']) && is_numeric($data['position'])) {
            $update_fields[] = "position = :position";
            $params[':position'] = $data['position'];
        }

        // Для кастомных ссылок можно обновлять все
        if ($current_link['source_table'] === 'custom') {
            if (isset($data['name'])) {
                $update_fields[] = "name = :name";
                $params[':name'] = $data['name'];
            }
            if (isset($data['link'])) {
                $update_fields[] = "link = :link";
                $params[':link'] = $data['link'];
            }
            if (isset($data['section']) && in_array($data['section'], ['shop', 'installation', 'client'])) {
                $update_fields[] = "section = :section";
                $params[':section'] = $data['section'];
            }
        }
        
        if (empty($update_fields)) {
            echo $this->error('Нет данных для обновления', 400);
            return;
        }
        
        $query = "UPDATE Footer SET " . implode(', ', $update_fields) . " WHERE footer_id = :id";
        
        try {
            $is_success = $this->executePost($query, $params);
            if ($is_success) {
                echo $this->success('Ссылка успешно обновлена');
            } else {
                echo $this->error('Ошибка при обновлении ссылки');
            }
        } catch (\Exception $e) {
            echo $this->error('Ошибка при обновлении ссылки: ' . $e->getMessage());
        }
    }

    public function deleteLink($id)
    {
        if (!$id) {
            echo $this->error('ID ссылки не указан');
            return;
        }

        $link = $this->getLinkById($id);
        if (!$link) {
            echo $this->error('Ссылка с таким ID не найдена');
            return;
        }

        if ($link['source_table'] !== 'custom') {
            echo $this->error('Эту ссылку нельзя удалить через API, так как она синхронизируется автоматически. Скройте ее, изменив флаг visible.', 403);
            return;
        }

        try {
            $query = "DELETE FROM Footer WHERE footer_id = :id AND source_table = 'custom'";
            $is_success = $this->executePost($query, [':id' => $id]);
            if ($is_success) {
                echo $this->success('Ссылка успешно удалена');
            } else {
                echo $this->error('Ошибка при удалении ссылки');
            }
        } catch (\Exception $e) {
            echo $this->error('Ошибка при удалении ссылки: ' . $e->getMessage());
        }
    }

    public function updatePositions($items)
    {
        if (empty($items) || !is_array($items)) {
            echo $this->error("Нет данных для обновления позиций", 400);
            return;
        }
        try {
            $this->pdo->beginTransaction();
            $query = "UPDATE Footer SET position = :position WHERE footer_id = :id";
            $stmt = $this->pdo->prepare($query);
            foreach ($items as $item) {
                if (isset($item['footer_id']) && isset($item['position'])) {
                    $stmt->execute([
                        ':id' => $item['footer_id'],
                        ':position' => $item['position'],
                    ]);
                }
            }
            $this->pdo->commit();
            echo $this->success('Позиции успешно обновлены');
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            error_log("Ошибка обновления позиций футера: " . $e->getMessage());
            echo $this->error("Ошибка обновления позиций", 500);
        }
    }
}

$api = new FooterAPI();
$api->handleRequest();
