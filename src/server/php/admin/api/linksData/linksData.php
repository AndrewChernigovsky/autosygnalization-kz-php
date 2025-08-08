<?php

namespace API\ADMIN;


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


class LinksDataAPI extends DataBase {
    protected $pdo;

    public function __construct() {
        $this->pdo = DataBase::getInstance()->getPdo();
    }

    private function success($data, $statusCode = 200) {
        http_response_code($statusCode);
        return json_encode(['success' => true, 'data' => $data]);
    }

    private function error($message, $statusCode = 400) {
        http_response_code($statusCode);
        return json_encode(['success' => false, 'error' => $message]);
    }

    private function executeQuery($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getAllLinksData() {
        try {
            $stmt = $this->executeQuery("SELECT * FROM LinksData ORDER BY links_data_id ASC");
            return $this->success($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'GET':
                echo $this->getAllLinksData();
                break;
            default:
                echo $this->error('Неверный метод запроса, доступны только GET запросы', 405);
        }
    }
}

$api = new LinksDataAPI();
$api->handleRequest();
