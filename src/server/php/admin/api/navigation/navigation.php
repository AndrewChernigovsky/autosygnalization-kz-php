<?php

namespace API\ADMIN;
// CORS-заголовки для разрешения запросов с фронта (например, React на localhost:5173)
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\DataBase;
use DATABASE\InitDataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
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

  // Получение всех элементов навигации
  public function getNavItems()
  {
    try {
      $query = "SELECT * FROM Navigation ORDER BY `sort_order` ASC, id ASC";
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

  // Обновление порядка элементов навигации
  public function updateNavItemsOrder($orderData)
  {
    try {
      $this->pdo->beginTransaction();

      foreach ($orderData as $item) {
        $query = "UPDATE Navigation SET `sort_order` = ?" . 
                 (isset($item['on_page']) ? ", on_page = ?" : "") . 
                 " WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        
        $params = [$item['sort_order']];
        if (isset($item['on_page'])) {
          $params[] = $item['on_page'] === 'true' || $item['on_page'] === true ? 1 : 0;
        }
        $params[] = $item['id'];
        
        $stmt->execute($params);
      }

      $this->pdo->commit();
      error_log("Обновлен порядок для " . count($orderData) . " элементов навигации");
      return $this->success(['message' => 'Порядок обновлен']);
    } catch (\Exception $e) {
      $this->pdo->rollBack();
      error_log("Ошибка обновления порядка навигации: " . $e->getMessage());
      return $this->error("Ошибка обновления порядка", 500);
    }
  }

  // Создание нового элемента навигации
  public function createNavItem($data, $icon = null)
  {
    try {
      error_log(print_r($data, true));

      $iconPath = null;

      // Обработка загрузки файла
      if ($icon) {
        $maxSize = 50 * 1024; // 50KB максимум
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/navigation/icons/';

        if ($icon['error'] !== UPLOAD_ERR_OK) {
          return $this->error("Ошибка загрузки иконки: " . $icon['error'], 400);
        }

        $allowedTypes = ['image/svg+xml', 'image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'];
        if (!in_array($icon['type'], $allowedTypes)) {
          return $this->error("Неверный формат иконки. Разрешены: SVG, PNG, JPG, JPEG, GIF, WebP", 400);
        }

        if ($icon['size'] > $maxSize) {
          return $this->error("Слишком большой файл. Максимум 50KB", 400);
        }

        if (!is_dir($uploadDir)) {
          if (!mkdir($uploadDir, 0755, true)) {
            return $this->error("Не удалось создать директорию для загрузки", 500);
          }
        }

        $extension = pathinfo($icon['name'], PATHINFO_EXTENSION);
        $uniqueName = 'icon-' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $uniqueName;

        if (!move_uploaded_file($icon['tmp_name'], $uploadPath)) {
          return $this->error("Не удалось сохранить иконку", 500);
        }

        $iconPath = '/server/uploads/navigation/icons/' . $uniqueName;
        error_log("Файл успешно загружен: " . $iconPath);
      }

      // Получаем максимальный порядок для нового элемента
      $orderQuery = "SELECT COALESCE(MAX(`sort_order`), 0) + 1 as next_order FROM Navigation";
      $orderStmt = $this->pdo->prepare($orderQuery);
      $orderStmt->execute();
      $nextOrder = $orderStmt->fetch(\PDO::FETCH_ASSOC)['next_order'];

      $query = "INSERT INTO Navigation (title, content, link, icon_path, `sort_order`, on_page) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $this->pdo->prepare($query);
      
      $stmt->execute([
        $data['title'],
        $data['content'] ?? '',
        $data['link'],
        $iconPath,
        $nextOrder,
        $data['on_page'] === 'true' || $data['on_page'] === true ? 1 : 0
      ]);

      $navItemId = $this->pdo->lastInsertId();
      error_log("Создан элемент навигации ID: " . $navItemId);

      return $this->success(['id' => $navItemId, 'message' => 'Элемент навигации создан'], 201);
    } catch (\Exception $e) {
      error_log("Ошибка создания элемента навигации: " . $e->getMessage());
      return $this->error("Ошибка создания элемента навигации: " . $e->getMessage(), 400);
    }
  }

  // Обновление элемента навигации
  public function updateNavItem($id, $data, $icon = null)
  {
    try {
      error_log("Обновление элемента навигации ID: " . $id);
      error_log(print_r($data, true));

      // Получаем текущие данные элемента
      $currentQuery = "SELECT * FROM Navigation WHERE id = ?";
      $currentStmt = $this->pdo->prepare($currentQuery);
      $currentStmt->execute([$id]);
      $currentData = $currentStmt->fetch(\PDO::FETCH_ASSOC);

      if (!$currentData) {
        return $this->error("Элемент навигации не найден", 404);
      }

      $iconPath = $currentData['icon_path'];

      // Если пришла новая иконка — обрабатываем её
      if ($icon) {
        $maxSize = 50 * 1024; // 50KB максимум
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/navigation/icons/';

        if ($icon['error'] !== UPLOAD_ERR_OK) {
          return $this->error("Ошибка загрузки иконки: " . $icon['error'], 400);
        }

        $allowedTypes = ['image/svg+xml', 'image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'];
        if (!in_array($icon['type'], $allowedTypes)) {
          return $this->error("Неверный формат иконки. Разрешены: SVG, PNG, JPG, JPEG, GIF, WebP", 400);
        }

        if ($icon['size'] > $maxSize) {
          return $this->error("Слишком большой файл. Максимум 50KB", 400);
        }

        if (!is_dir($uploadDir)) {
          if (!mkdir($uploadDir, 0755, true)) {
            return $this->error("Не удалось создать директорию для загрузки", 500);
          }
        }

        // Удаляем старый файл, если он существует
        if ($iconPath && file_exists($_SERVER['DOCUMENT_ROOT'] . $iconPath)) {
          unlink($_SERVER['DOCUMENT_ROOT'] . $iconPath);
        }

        $extension = pathinfo($icon['name'], PATHINFO_EXTENSION);
        $uniqueName = 'icon-' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $uniqueName;

        if (!move_uploaded_file($icon['tmp_name'], $uploadPath)) {
          return $this->error("Не удалось сохранить иконку", 500);
        }

        $iconPath = '/server/uploads/navigation/icons/' . $uniqueName;
        error_log("Файл успешно обновлен: " . $iconPath);
      }

      $query = "UPDATE Navigation SET title = ?, content = ?, link = ?, icon_path = ?, on_page = ? WHERE id = ?";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute([
        $data['title'],
        $data['content'] ?? $currentData['content'],
        $data['link'],
        $iconPath,
        $data['on_page'] === 'true' || $data['on_page'] === true ? 1 : 0,
        $id
      ]);

      if ($stmt->rowCount() === 0) {
        // Если данные не изменились, это не ошибка - просто возвращаем успех
        error_log("Данные элемента навигации не изменились ID: " . $id);
        return $this->success(['message' => 'Данные не изменились']);
      }

      error_log("Элемент навигации обновлен ID: " . $id);
      return $this->success(['message' => 'Элемент навигации обновлен']);
    } catch (\Exception $e) {
      error_log("Ошибка обновления элемента навигации: " . $e->getMessage());
      return $this->error("Ошибка обновления элемента навигации", 500);
    }
  }

  // Удаление элемента навигации
  public function deleteNavItem($id)
  {
    try {
      // Получаем данные элемента для удаления файла
      $query = "SELECT icon_path FROM Navigation WHERE id = ?";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute([$id]);
      $navItemData = $stmt->fetch(\PDO::FETCH_ASSOC);

      if (!$navItemData) {
        return $this->error("Элемент навигации не найден", 404);
      }

      // Удаляем файл, если он существует
      if ($navItemData['icon_path'] && file_exists($_SERVER['DOCUMENT_ROOT'] . $navItemData['icon_path'])) {
        unlink($_SERVER['DOCUMENT_ROOT'] . $navItemData['icon_path']);
      }

      // Удаляем элемент из базы данных
      $deleteQuery = "DELETE FROM Navigation WHERE id = ?";
      $deleteStmt = $this->pdo->prepare($deleteQuery);
      $deleteStmt->execute([$id]);

      if ($deleteStmt->rowCount() === 0) {
        return $this->error("Элемент навигации не найден", 404);
      }

      error_log("Элемент навигации удален ID: " . $id);
      return $this->success(['message' => 'Элемент навигации удален']);
    } catch (\Exception $e) {
      error_log("Ошибка удаления элемента навигации: " . $e->getMessage());
      return $this->error("Ошибка удаления элемента навигации", 500);
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
  
  // Устанавливаем Content-Type только если он реально существует
  $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

  $input = [];
  $icon = null;

  // Для GET и DELETE запросов нам не нужен Content-Type, пропускаем проверку
  if ($method !== 'GET' && $method !== 'DELETE') {
      if (strpos($contentType, 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
          http_response_code(400);
          echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
          exit;
        }
      } elseif (strpos($contentType, 'multipart/form-data') !== false) {
        $input = $_POST;

        if (isset($_FILES['icon_path']) && $_FILES['icon_path']['error'] === UPLOAD_ERR_OK) {
          $icon = $_FILES['icon_path'];
        } else {
          $icon = null;
        }
      } else {
        // Если Content-Type не задан или не поддерживается для POST/PUT/PATCH, выдаем ошибку
        http_response_code(415);
        echo json_encode(['success' => false, 'error' => 'Unsupported Media Type: ' . $contentType]);
        exit;
      }
  }

  switch ($method) {
    case 'GET':
      echo $api->getNavItems();
      break;

    case 'POST':
      // Проверяем, есть ли ID в запросе, чтобы определить, обновление это или создание
      $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

      if ($id) {
        // --- Это обновление (метод PUT через POST) ---
        if (!$input && !$icon) {
            echo $api->error("Данные для обновления не переданы");
            break;
        }
        // Вызываем updateNavItem, передавая ID, данные и, возможно, иконку
        echo $api->updateNavItem($id, $input, $icon);

      } else {
        // --- Это создание ---
        if ($input && strpos($contentType, 'application/json') !== false) {
            echo $api->createNavItem($input);
        } elseif ($input && strpos($contentType,'multipart/form-data') !== false) {
            // Эта ветка обработает и создание с иконкой, и без нее
            echo $api->createNavItem($input, $icon);
        } else {
            echo $api->error("Данные для создания не переданы");
        }
      }
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
      if ($input && strpos($contentType, 'application/json') !== false && $id) {
          echo $api->updateNavItem($id, $input);
      break;
      } elseif ($input && $icon && strpos($contentType,'multipart/form-data') !== false && $id) {
          echo $api->updateNavItem($id, $input, $icon);
        break;
      } elseif ($input && strpos($contentType,'multipart/form-data') !== false) {
         echo $api->updateNavItem($id, $input);
        break;
      } else {
          echo $api->error("Данные не переданы");
        break;
      }

    case 'PATCH':
      if (isset($_GET['action']) && $_GET['action'] === 'update-sort_order') {
        if (!$input) {
          echo $api->error("Данные не переданы");
          break;
        }
        echo $api->updateNavItemsOrder($input);
      } else {
        echo $api->error("Неизвестное действие", 400);
      }
      break;

    case 'DELETE':
      if (!isset($_GET['id'])) {
        echo $api->error("ID не указан");
        break;
      }
      echo $api->deleteNavItem($_GET['id']);
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

