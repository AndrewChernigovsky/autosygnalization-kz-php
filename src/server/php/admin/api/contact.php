<?php

namespace API\ADMIN;
// CORS-заголовки для разрешения запросов с фронта (например, React на localhost:5173)
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

class ContactAPI extends DataBase
{
  protected $pdo;
    
  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  // Получение всех контактов
  public function getContacts()
  {
    try {
      $query = "SELECT * FROM Contacts ORDER BY contact_id ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();
      $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      error_log("Получены все элементы контактов: " . count($result));
      return $this->success($result);
    } catch (\Exception $e) {
      error_log("Ошибка получения контактов: " . $e->getMessage());
      return $this->error("Ошибка получения контактов", 500);
    }
  }

  // Создание нового контакта
  public function createContact($data)
  {
    try {
      error_log(print_r($data, true));

      $query = "INSERT INTO Contacts (type,title,content,link,icon_path) VALUES (?, ?, ?, ?, ?)";

      $stmt = $this->pdo->prepare($query);
      $stmt->execute([
        $data['type'],
        $data['title'],
        $data['content'],
        $data['link'],
        $data['icon_path'],
      ]);

      $contactId = $this->pdo->lastInsertId();
      error_log("Создан элемент контактов ID: " . $contactId);

      return $this->success(['contact_id' => $contactId, 'message' => 'Элемент контактов создан'], 201);
    } catch (\Exception $e) {
      error_log("Ошибка создания элемента контактов:" . $e->getMessage());
      return $this->error("Ошибка создания элемента контактов:" . $e->getMessage(), 400);
    }
  }

  //Обновление контакта в бд
  public function updateContact($id, $data)
  {
    try {
      $query = "UPDATE Contacts SET
                type = :type,
                title = :title,
                content = :content,
                link = :link
                svg_path = :svg_path
                WHERE contact_id = :id";

      $stmt = $this->pdo->prepare($query);
      $stmt->execute([
        ':type' => $data['type'],
        ':title' => $data['title'],
        ':content' => $data['content'],
        ':link' => $data['link'],
        ':svg_path' => $data['svg_path'],
        ':id' => $id
      ]);

      if ($stmt->rowCount() === 0) {
        return $this->error("Элемент контакта не найден", 404);
      }

      return $this->success(['message' => 'Элемент контактов обновлен']);
    } catch (\Exception $e) {
      error_log("Ошибка обновления контакта: " . $e->getMessage());
      return $this->error("Ошибка обновления контакта", 500);
    }
  }

  //Удаление контакта с бд

  public function deleteContactItem($id)
  {
    try {
      $query = "DELETE FROM Contacts WHERE contact_id = :id";

      $stmt = $this->pdo->prepare($query);
      $stmt->execute([':id' => $id]);


      if ($stmt->rowCount() === 0) {
        return $this->error("Элемент контактов не найден", 404);
      }

      return $this->success(['message' => 'Элемент контактов удален']);
    } catch (\Exception $e) {
      error_log("Ошибка удаления контактов: " . $e->getMessage());
      return $this->error("Ошибка удаления контактов", 500);
    }
  }

  // Ответы ошибка и успех
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

// Основная логика запуска API
try {
  $api = new ContactAPI();
  $method = $_SERVER['REQUEST_METHOD'];
  $input = json_decode(file_get_contents('php://input'), true);

  switch ($method) {
    case 'GET':
      echo $api->getContacts();
      break;

    case 'POST':
      if (!$input) {
      echo $api->error("Данные не переданы");
      break;
      }
      echo $api->createContact($input);
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
      echo $api->updateContact($id, $input);
      break;
    
    case 'DELETE':
      $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
      if (!$id) {
          echo $api->error("ID не указан");
          break;
      }
      echo $api->deleteContactItem($id);
      break;


    default:
      echo $api->error("Метод не поддерживается", 405);
      break;
  }
} catch (\Exception $e) {
  error_log("Критическая ошибка API контактов: " . $e->getMessage());
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'error' => 'Внутренняя ошибка сервера'
  ]);
}

