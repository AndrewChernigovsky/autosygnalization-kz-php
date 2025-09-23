<?php

namespace API\ADMIN;

// CORS-заголовки
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

class AdvantageAPI extends DataBase
{
  protected $pdo;
    
  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  public function getAllAdvantage()
  {
    try {
      $query = "SELECT advantage_id, content, image_path, position FROM Advantage ORDER BY position ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();
      return $this->success($stmt->fetchAll(\PDO::FETCH_ASSOC));
    } catch (\Exception $e) {
      error_log("Ошибка получения данных 'Преимущества': " . $e->getMessage());
      return $this->error("Ошибка получения данных", 500);
    }
  }

  public function createAdvantage($data, $files)
  {

    $stmt_pos = $this->pdo->prepare("SELECT MAX(position) FROM Advantage");
    $stmt_pos->execute();
    $max_pos = $stmt_pos->fetchColumn();
    $new_position = ($max_pos === null) ? 1 : (int)$max_pos + 1;

    $params = [
      ':content' => $data['content'] ?? null,
      ':position' => $new_position,
      ':image_path' => null,
    ];

    if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
      $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/advantage/images/';
      if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
      
      $file_extension = pathinfo($files['image']['name'], PATHINFO_EXTENSION);
      $new_filename = uniqid('advantage_', true) . '.' . $file_extension;
      $upload_file = $upload_dir . $new_filename;

      if (move_uploaded_file($files['image']['tmp_name'], $upload_file)) {
        $params[':image_path'] = '/server/uploads/advantage/images/' . $new_filename;
      } else {
        return $this->error('Ошибка при перемещении загруженного файла.', 500);
      }
    }

    try {
      $query = "INSERT INTO Advantage (content, image_path, position) VALUES (:content, :image_path, :position)";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute($params);
      
      $new_id = $this->pdo->lastInsertId();

      $new_item = [
        'advantage_id' => $new_id,
        'content' => $params[':content'],
        'image_path' => $params[':image_path'],
        'position' => $new_position
      ];
      
      return $this->success($new_item);
    } catch (\Exception $e) {
      error_log("Ошибка создания записи 'Преимущества': " . $e->getMessage());
      return $this->error("Ошибка создания записи", 500);
    }
  }

  public function updateAdvantage($data, $files)
  {
    if (!isset($data['advantage_id'])) {
      return $this->error("Отсутствует ID для обновления", 400);
    }
    $id = $data['advantage_id'];
    
    $update_fields = [];
    $params = [':id' => $id];
    $new_image_path = null;

    // Сначала получаем путь к старому изображению
    $stmt_old = $this->pdo->prepare("SELECT image_path FROM Advantage WHERE advantage_id = :id");
    $stmt_old->execute([':id' => $id]);
    $old_image_path = $stmt_old->fetchColumn();

    // Проверяем, пришел ли новый файл
    if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
      $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/advantage/images/';
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }
      
      $file_extension = pathinfo($files['image']['name'], PATHINFO_EXTENSION);
      $new_filename = uniqid('advantage_', true) . '.' . $file_extension;
      $upload_file = $upload_dir . $new_filename;

      if (move_uploaded_file($files['image']['tmp_name'], $upload_file)) {
        // Если есть старый файл, удаляем его
        if ($old_image_path && file_exists($_SERVER['DOCUMENT_ROOT'] . $old_image_path)) {
          unlink($_SERVER['DOCUMENT_ROOT'] . $old_image_path);
        }
        
        $new_image_path = '/server/uploads/advantage/images/' . $new_filename;
        $update_fields[] = 'image_path = :image_path';
        $params[':image_path'] = $new_image_path;
      } else {
        return $this->error('Ошибка при перемещении загруженного файла.', 500);
      }
    } 
    // Если нового файла нет, но есть флаг на удаление старого
    else if (isset($data['remove_image']) && $data['remove_image'] == '1') {
      if ($old_image_path && file_exists($_SERVER['DOCUMENT_ROOT'] . $old_image_path)) {
        if (!unlink($_SERVER['DOCUMENT_ROOT'] . $old_image_path)) {
            // Если удаление не удалось, можно записать ошибку в лог или вернуть ошибку
            error_log("Не удалось удалить старый файл: " . $old_image_path);
            // В зависимости от требований, можно прервать выполнение или просто продолжить
        }
      }
      $update_fields[] = 'image_path = NULL';
    }

    if (isset($data['title'])) {
      $update_fields[] = 'title = :title';
      $params[':title'] = $data['title'];
    }
    if (isset($data['content'])) {
      $update_fields[] = 'content = :content';
      $params[':content'] = $data['content'];
    }

    if (empty($update_fields)) {
      return $this->success(['advantage_id' => $id], "Нет данных для обновления.");
    }

    try {
      $query = "UPDATE Advantage SET " . implode(', ', $update_fields) . " WHERE advantage_id = :id";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute($params);

      $response_data = ['advantage_id' => $id];
      if ($new_image_path) {
        $response_data['image_path'] = $new_image_path;
      }

      return $this->success($response_data);
    } catch (\Exception $e) {
      error_log("Ошибка обновления записи 'Преимущества': " . $e->getMessage());
      return $this->error("Ошибка обновления записи", 500);
    }
  }

  public function updatePositions($items)
  {
    if (empty($items) || !is_array($items)) {
      return $this->error("Нет данных для обновления позиций", 400);
    }

    try {
      $this->pdo->beginTransaction();

      $query = "UPDATE Advantage SET position = :position WHERE advantage_id = :id";
      $stmt = $this->pdo->prepare($query);

      foreach ($items as $item) {
        if (isset($item['advantage_id']) && isset($item['position'])) {
          $stmt->execute([
            ':id' => $item['advantage_id'],
            ':position' => $item['position'],
          ]);
        }
      }

      $this->pdo->commit();
      return $this->success(['status' => 'Positions updated']);
    } catch (\Exception $e) {
      $this->pdo->rollBack();
      error_log("Ошибка обновления позиций: " . $e->getMessage());
      return $this->error("Ошибка обновления позиций", 500);
    }
  }

  /**
   * Удаляет запись из таблицы Advantage
   */
  public function deleteAdvantage($id)
  {
    if (!$id) {
      return $this->error("Отсутствует ID для удаления", 400);
    }
    
    try {
      // 1. Получаем путь к файлу перед удалением записи из БД
      $stmt_select = $this->pdo->prepare("SELECT image_path FROM Advantage WHERE advantage_id = :id");
      $stmt_select->execute([':id' => $id]);
      $image_path = $stmt_select->fetchColumn();

      // 2. Удаляем запись из базы данных
      $stmt_delete = $this->pdo->prepare("DELETE FROM Advantage WHERE advantage_id = :id");
      $stmt_delete->execute([':id' => $id]);

      // 3. Если запись успешно удалена и у нее был файл, удаляем сам файл
      if ($stmt_delete->rowCount() > 0 && $image_path) {
        $full_file_path = $_SERVER['DOCUMENT_ROOT'] . $image_path;
        if (file_exists($full_file_path)) {
          unlink($full_file_path);
        }
      }

      return $this->success(['deleted_id' => $id]);
    } catch (\Exception $e) {
      error_log("Ошибка удаления записи 'Преимущества': " . $e->getMessage());
      return $this->error("Ошибка удаления записи", 500);
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

// Основная логика API
try {
    $api = new AdvantageAPI();
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            echo $api->getAllAdvantage();
            break;

        case 'POST':
            $contentType = trim($_SERVER["CONTENT_TYPE"] ?? '');

            if (strpos($contentType, 'application/json') !== false) {
                // Обработка JSON-запроса для обновления позиций
                $input = json_decode(file_get_contents('php://input'), true);
                if (isset($input['action']) && $input['action'] === 'update_positions') {
                    echo $api->updatePositions($input['items'] ?? []);
                } else {
                    echo $api->error("Неверный JSON-запрос", 400);
                }
            } else {
                // Обработка form-data для создания или обновления
                if (isset($_POST['advantage_id'])) {
                    echo $api->updateAdvantage($_POST, $_FILES);
                } else {
                    echo $api->createAdvantage($_POST, $_FILES);
                }
            }
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            echo $api->deleteAdvantage($id);
            break;

        default:
            echo $api->error("Метод не поддерживается", 405);
            break;
    }
} catch (\Exception $e) {
    error_log("Критическая ошибка API 'Преимущества': " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Внутренняя ошибка сервера']);
}

