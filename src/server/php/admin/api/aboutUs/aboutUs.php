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

// Обработка pre-flight запросов
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit(0);
}

class AboutUsAPI extends DataBase
{
  protected $pdo;
    
  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  /**
   * Получает все записи из таблицы AboutUs
   */
  public function getAboutUs()
  {
    try {
      $query = "SELECT * FROM AboutUs ORDER BY FIELD(type, 'present-slogan', 'present-text', 'advantages-item', 'comment', 'tech-photo-image', 'appeal-text'), position ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();
      return $this->success($stmt->fetchAll(\PDO::FETCH_ASSOC));
    } catch (\Exception $e) {
      error_log("Ошибка получения данных 'О нас': " . $e->getMessage());
      return $this->error("Ошибка получения данных", 500);
    }
  }

  public function createAboutUs($data, $files)
  {
    if (!isset($data['type'])) {
      return $this->error("Отсутствует 'type' для создания записи", 400);
    }

    $type = $data['type'];

    $stmt_pos = $this->pdo->prepare("SELECT MAX(position) FROM AboutUs WHERE type = :type");
    $stmt_pos->execute([':type' => $type]);
    $max_pos = $stmt_pos->fetchColumn();
    $new_position = ($max_pos === null) ? 1 : (int)$max_pos + 1;

    $params = [
      ':type' => $type,
      ':title' => $data['title'] ?? null,
      ':content' => $data['content'] ?? null,
      ':position' => $new_position,
      ':image_path' => null,
    ];

    if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
      $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/about_us/images/';
      if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
      
      $file_extension = pathinfo($files['image']['name'], PATHINFO_EXTENSION);
      $new_filename = uniqid('about_', true) . '.' . $file_extension;
      $upload_file = $upload_dir . $new_filename;

      if (move_uploaded_file($files['image']['tmp_name'], $upload_file)) {
        $params[':image_path'] = '/server/uploads/about_us/images/' . $new_filename;
      } else {
        return $this->error('Ошибка при перемещении загруженного файла.', 500);
      }
    }

    try {
      $query = "INSERT INTO AboutUs (type, title, content, image_path, position) VALUES (:type, :title, :content, :image_path, :position)";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute($params);
      
      $new_id = $this->pdo->lastInsertId();

      $new_item = [
        'about_us_id' => $new_id,
        'type' => $type,
        'title' => $params[':title'],
        'content' => $params[':content'],
        'image_path' => $params[':image_path'],
        'position' => $new_position
      ];
      
      return $this->success($new_item);
    } catch (\Exception $e) {
      error_log("Ошибка создания записи 'О нас': " . $e->getMessage());
      return $this->error("Ошибка создания записи", 500);
    }
  }

  /**
   * Обновляет запись в таблице AboutUs
   */
  public function updateAboutUs($data, $files)
  {
    if (!isset($data['about_us_id'])) {
      return $this->error("Отсутствует ID для обновления", 400);
    }
    $id = $data['about_us_id'];
    
    $update_fields = [];
    $params = [':id' => $id];
    $new_image_path = null;

    if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
      $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/about_us/images/';
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }
      
      $file_extension = pathinfo($files['image']['name'], PATHINFO_EXTENSION);
      $new_filename = uniqid('about_', true) . '.' . $file_extension;
      $upload_file = $upload_dir . $new_filename;

      if (move_uploaded_file($files['image']['tmp_name'], $upload_file)) {
        $stmt_old = $this->pdo->prepare("SELECT image_path FROM AboutUs WHERE about_us_id = :id");
        $stmt_old->execute([':id' => $id]);
        $old_image_path = $stmt_old->fetchColumn();
        if ($old_image_path && file_exists($_SERVER['DOCUMENT_ROOT'] . $old_image_path)) {
          unlink($_SERVER['DOCUMENT_ROOT'] . $old_image_path);
        }
        
        $new_image_path = '/server/uploads/about_us/images/' . $new_filename;
        $update_fields[] = 'image_path = :image_path';
        $params[':image_path'] = $new_image_path;
      } else {
        return $this->error('Ошибка при перемещении загруженного файла.', 500);
      }
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
      return $this->success(['about_us_id' => $id], "Нет данных для обновления.");
    }

    try {
      $query = "UPDATE AboutUs SET " . implode(', ', $update_fields) . " WHERE about_us_id = :id";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute($params);

      $response_data = ['about_us_id' => $id];
      if ($new_image_path) {
        $response_data['image_path'] = $new_image_path;
      }

      return $this->success($response_data);
    } catch (\Exception $e) {
      error_log("Ошибка обновления записи 'О нас': " . $e->getMessage());
      return $this->error("Ошибка обновления записи", 500);
    }
  }

  /**
   * Удаляет запись из таблицы AboutUs
   */
  public function deleteAboutUs($id)
  {
    if (!$id) {
      return $this->error("Отсутствует ID для удаления", 400);
    }
    
    try {
      // 1. Получаем путь к файлу перед удалением записи из БД
      $stmt_select = $this->pdo->prepare("SELECT image_path FROM AboutUs WHERE about_us_id = :id");
      $stmt_select->execute([':id' => $id]);
      $image_path = $stmt_select->fetchColumn();

      // 2. Удаляем запись из базы данных
      $stmt_delete = $this->pdo->prepare("DELETE FROM AboutUs WHERE about_us_id = :id");
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
      error_log("Ошибка удаления записи 'О нас': " . $e->getMessage());
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
  $api = new AboutUsAPI();
  $method = $_SERVER['REQUEST_METHOD'];

  switch ($method) {
    case 'GET':
      echo $api->getAboutUs();
      break;

    case 'POST':
      if (isset($_POST['about_us_id'])) {
        echo $api->updateAboutUs($_POST, $_FILES);
      } else {
        echo $api->createAboutUs($_POST, $_FILES);
      }
      break;

    case 'DELETE':
      $id = $_GET['id'] ?? null;
      echo $api->deleteAboutUs($id);
      break;

    default:
      echo $api->error("Метод не поддерживается", 405);
      break;
  }
} catch (\Exception $e) {
  error_log("Критическая ошибка API 'О нас': " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['success' => false, 'error' => 'Внутренняя ошибка сервера']);
}

