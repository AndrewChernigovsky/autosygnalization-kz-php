<?php

namespace API\ADMIN;
require_once __DIR__ . '/../../../vendor/autoload.php';

// require_once __DIR__ . '/../../database/DataBase.php';

use DATABASE\DataBase;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  exit(0);
}

class NavigationAPI extends DataBase
{
  protected $pdo;

  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  public function getAllNavigation()
  {
    try {
      $query = "SELECT * FROM Navigation ORDER BY position ASC, navigation_id ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();
      $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      error_log("Получены все элементы навигации: " . count($result));
      return $this->success($result);
    } catch (\Exception $e) {
      error_log("Ошибка получения навигации: " . $e->getMessage());
      return $this->error("Ошибка получения навигации", 500);
    }
  }

  public function getNavigationById($id)
  {
    try {
      $query = "SELECT * FROM Navigation WHERE navigation_id = ?";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute([$id]);
      $result = $stmt->fetch(\PDO::FETCH_ASSOC);

      if (!$result) {
        return $this->error("Элемент навигации не найден", 404);
      }

      error_log("Получен элемент навигации ID: " . $id);
      return $this->success($result);
    } catch (\Exception $e) {
      error_log("Ошибка получения элемента навигации: " . $e->getMessage());
      return $this->error("Ошибка получения элемента навигации", 500);
    }
  }

  public function createNavigation($data)
  {
    try {

      $this->validateNavigation($data);

      $query = "INSERT INTO Navigation (title, slug, href, parent_id, position, is_active, icon, target) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

      $stmt = $this->pdo->prepare($query);
      $stmt->execute([
        $data['title'],
        $data['slug'],
        $data['href'],
        $data['parent_id'] ?? null,
        $data['position'] ?? 0,
        $data['is_active'] ?? true,
        $data['icon'] ?? null,
        $data['target'] ?? '_self'
      ]);

      $navigationId = $this->pdo->lastInsertId();
      error_log("Создан элемент навигации ID: " . $navigationId);

      return $this->success(['navigation_id' => $navigationId, 'message' => 'Элемент навигации создан'], 201);
    } catch (\Exception $e) {
      error_log("Ошибка создания элемента навигации: " . $e->getMessage());
      return $this->error("Ошибка создания элемента навигации: " . $e->getMessage(), 400);
    }
  }

  public function updateNavigation($id, $data)
  {
    try {
      // Проверим существует ли элемент
      $existsQuery = "SELECT navigation_id FROM Navigation WHERE navigation_id = ?";
      $existsStmt = $this->pdo->prepare($existsQuery);
      $existsStmt->execute([$id]);

      if (!$existsStmt->fetch()) {
        return $this->error("Элемент навигации не найден", 404);
      }

      $this->validateNavigation($data, $id);

      $query = "UPDATE Navigation SET 
                     title = ?, slug = ?, href = ?, parent_id = ?, 
                     position = ?, is_active = ?, icon = ?, target = ?
                     WHERE navigation_id = ?";

      $stmt = $this->pdo->prepare($query);
      $stmt->execute([
        $data['title'],
        $data['slug'],
        $data['href'],
        $data['parent_id'] ?? null,
        $data['position'] ?? 0,
        $data['is_active'] ?? true,
        $data['icon'] ?? null,
        $data['target'] ?? '_self',
        $id
      ]);

      error_log("Обновлен элемент навигации ID: " . $id);
      return $this->success(['message' => 'Элемент навигации обновлен']);
    } catch (\Exception $e) {
      error_log("Ошибка обновления элемента навигации: " . $e->getMessage());
      return $this->error("Ошибка обновления элемента навигации: " . $e->getMessage(), 400);
    }
  }

  public function deleteNavigation($id)
  {
    try {
      // Проверим существует ли элемент
      $existsQuery = "SELECT navigation_id FROM Navigation WHERE navigation_id = ?";
      $existsStmt = $this->pdo->prepare($existsQuery);
      $existsStmt->execute([$id]);

      if (!$existsStmt->fetch()) {
        return $this->error("Элемент навигации не найден", 404);
      }

      $query = "DELETE FROM Navigation WHERE navigation_id = ?";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute([$id]);

      error_log("Удален элемент навигации ID: " . $id);
      return $this->success(['message' => 'Элемент навигации удален']);
    } catch (\Exception $e) {
      error_log("Ошибка удаления элемента навигации: " . $e->getMessage());
      return $this->error("Ошибка удаления элемента навигации", 500);
    }
  }

  private function validateNavigation($data, $excludeId = null)
  {
    if (empty($data['title'])) {
      throw new \InvalidArgumentException("Поле title обязательно");
    }
    if (empty($data['slug'])) {
      throw new \InvalidArgumentException("Поле slug обязательно");
    }
    if (empty($data['href'])) {
      throw new \InvalidArgumentException("Поле href обязательно");
    }

    // Проверим уникальность slug
    $query = "SELECT navigation_id FROM Navigation WHERE slug = ?";
    $params = [$data['slug']];

    if ($excludeId) {
      $query .= " AND navigation_id != ?";
      $params[] = $excludeId;
    }

    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);

    if ($stmt->fetch()) {
      throw new \InvalidArgumentException("Slug уже существует");
    }
  }

  public function success($data, $statusCode = 200)
  {
    http_response_code($statusCode);
    return json_encode([
      'success' => true,
      'data' => $data
    ]);
  }

  public function error($message, $statusCode = 400)
  {
    http_response_code($statusCode);
    return json_encode([
      'success' => false,
      'error' => $message
    ]);
  }
}

// Обработка запросов
try {
  $api = new NavigationAPI();
  $method = $_SERVER['REQUEST_METHOD'];
  $input = json_decode(file_get_contents('php://input'), true);

  switch ($method) {
    case 'GET':
      if (isset($_GET['id'])) {
        echo $api->getNavigationById($_GET['id']);
      } else {
        echo $api->getAllNavigation();
      }
      break;

    case 'POST':
      if (!$input) {
        echo $api->error("Данные не переданы");
        break;
      }
      echo $api->createNavigation($input);
      break;

    case 'PUT':
      if (!isset($_GET['id'])) {
        echo $api->error("ID не указан");
        break;
      }
      if (!$input) {
        echo $api->error("Данные не переданы");
        break;
      }
      echo $api->updateNavigation($_GET['id'], $input);
      break;

    case 'DELETE':
      if (!isset($_GET['id'])) {
        echo $api->error("ID не указан");
        break;
      }
      echo $api->deleteNavigation($_GET['id']);
      break;

    default:
      echo $api->error("Метод не поддерживается", 405);
      break;
  }
} catch (\Exception $e) {
  error_log("Критическая ошибка API навигации: " . $e->getMessage());
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'error' => 'Внутренняя ошибка сервера'
  ]);
}
