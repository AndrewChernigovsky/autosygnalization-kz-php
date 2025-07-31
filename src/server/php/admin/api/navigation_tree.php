<?php

namespace API\ADMIN;
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

require_once __DIR__ . '/../../../vendor/autoload.php';

use DATABASE\DataBase;


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit(0);
}

class NavigationTreeAPI extends DataBase
{
  protected $pdo;

  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  public function updateNavigation($id, $data)
  {
    try {
      $query = "UPDATE Navigation SET
                title = :title,
                slug = :slug,
                href = :href,
                parent_id = :parent_id,
                position = :position,
                is_active = :is_active,
                icon = :icon,
                target = :target
                WHERE navigation_id = :id";

      $stmt = $this->pdo->prepare($query);
      $stmt->execute([
        ':title' => $data['title'],
        ':slug' => $data['slug'],
        ':href' => $data['href'],
        ':parent_id' => $data['parent_id'],
        ':position' => $data['position'],
        ':is_active' => $data['is_active'],
        ':icon' => $data['icon'],
        ':target' => $data['target'],
        ':id' => $id
      ]);

      if ($stmt->rowCount() === 0) {
        return $this->error("Элемент навигации не найден", 404);
      }

      return $this->success(['message' => 'Элемент навигации обновлен']);
    } catch (\Exception $e) {
      error_log("Ошибка обновления навигации: " . $e->getMessage());
      return $this->error("Ошибка обновления навигации", 500);
    }
  }

  public function deleteNavigationItem($id)
  {
    try {
      $query = "DELETE FROM Navigation WHERE navigation_id = :id";

      $stmt = $this->pdo->prepare($query);
      $stmt->execute([':id' => $id]);


      if ($stmt->rowCount() === 0) {
        return $this->error("Элемент навигации не найден", 404);
      }

      return $this->success(['message' => 'Элемент навигации удален']);
    } catch (\Exception $e) {
      error_log("Ошибка удаления навигации: " . $e->getMessage());
      return $this->error("Ошибка удаления навигации", 500);
    }
  }

  public function getNavigationTree()
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

  public function updateNavigationPositions($positionsData)
  {
    try {
      $this->pdo->beginTransaction();

      foreach ($positionsData as $item) {
        $query = "UPDATE Navigation SET position = ?, parent_id = ? WHERE navigation_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
          $item['position'],
          $item['parent_id'] ?? null,
          $item['navigation_id']
        ]);
      }

      $this->pdo->commit();
      error_log("Обновлены позиции для " . count($positionsData) . " элементов навигации");
      return $this->success(['message' => 'Позиции обновлены']);
    } catch (\Exception $e) {
      $this->pdo->rollBack();
      error_log("Ошибка обновления позиций навигации: " . $e->getMessage());
      return $this->error("Ошибка обновления позиций", 500);
    }
  }

  public function createNavigation($data)
  {
    try {

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

  private function buildTree($items, $parentId = null)
  {
    $tree = [];

    foreach ($items as $item) {
      if ($item['parent_id'] == $parentId) {
        $children = $this->buildTree($items, $item['navigation_id']);
        if (!empty($children)) {
          $item['children'] = $children;
        }
        $tree[] = $item;
      }
    }

    return $tree;
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
  $api = new NavigationTreeAPI();
  $method = $_SERVER['REQUEST_METHOD'];
  $input = json_decode(file_get_contents('php://input'), true);

  switch ($method) {
    case 'GET':
      echo $api->getNavigationTree();
      break;

    case 'POST':
      if (!$input) {
        echo $api->error("Данные не переданы");
        break;
      }
      echo $api->createNavigation($input);
      break;

    case 'PUT':
      if (!$input) {
        echo $api->error("Данные не переданы");
        break;
      }
      $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
      if (!$id) {
        echo $api->error("ID не указан");
        break;
      }
      echo $api->updateNavigation($id, $input);
      break;

    case 'DELETE':
      $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
      if (!$id) {
        echo $api->error("ID не указан");
        break;
      }
      echo $api->deleteNavigationItem($id);
      break;

    default:
      echo $api->error("Метод не поддерживается", 405);
      break;
  }
} catch (\Exception $e) {
  error_log("Критическая ошибка API дерева навигации: " . $e->getMessage());
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'error' => 'Внутренняя ошибка сервера'
  ]);
}
