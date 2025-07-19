<?php

namespace API\ADMIN;
// CORS-заголовки для разрешения запросов с фронта (например, React на localhost:5173)
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
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



      public function updateContact($id, $data, $icon = null)
      {
        try {
          $iconPath = null;

          // Если пришла новая иконка — обрабатываем её
          if ($icon) {
            $maxSize = 50 * 1024;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/contact/icons/';

            if ($icon['error'] !== UPLOAD_ERR_OK) {
              return $this->error("Ошибка загрузки иконки: " . $icon['error'], 400);
            }

            $allowedTypes = ['image/svg+xml'];
            if (!in_array($icon['type'], $allowedTypes)) {
              return $this->error("Неверный формат иконки. Разрешены SVG", 400);
            }

            if ($icon['size'] > $maxSize) {
              return $this->error("Слишком большой файл. Максимум 50KB", 400);
            }

            if (!is_dir($uploadDir)) {
              if (!mkdir($uploadDir, 0755, true)) {
                return $this->error("Не удалось создать директорию для загрузки", 500);
              }
            }

            // Удаляем старую иконку (если есть)
            $checkStmt = $this->pdo->prepare("SELECT icon_path FROM Contacts WHERE contact_id = ?");
            $checkStmt->execute([$id]);
            $old = $checkStmt->fetch(\PDO::FETCH_ASSOC);

            if ($old && !empty($old['icon_path'])) {
              $oldPath = $_SERVER['DOCUMENT_ROOT'] . $old['icon_path'];
              if (file_exists($oldPath)) {
                unlink($oldPath);
              }
            }

            // Сохраняем новую иконку
            $extension = pathinfo($icon['name'], PATHINFO_EXTENSION);
            $uniqueName = 'icon-' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $uniqueName;

            if (!move_uploaded_file($icon['tmp_name'], $uploadPath)) {
              return $this->error("Не удалось сохранить иконку", 500);
            }

            $iconPath = '/server/uploads/contact/icons/' . $uniqueName;
          } else {
            // Если иконка НЕ передана — оставляем старую
            $stmt = $this->pdo->prepare("SELECT icon_path FROM Contacts WHERE contact_id = ?");
            $stmt->execute([$id]);
            $existing = $stmt->fetch(\PDO::FETCH_ASSOC);
            $iconPath = $existing['icon_path'] ?? null;
          }

          // Обновляем контакт
          $query = "UPDATE Contacts SET
                    type = :type,
                    title = :title,
                    content = :content,
                    link = :link,
                    icon_path = :icon_path
                    WHERE contact_id = :id";

          $stmt = $this->pdo->prepare($query);
          $stmt->execute([
            ':type' => $data['type'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':link' => $data['link'],
            ':icon_path' => $iconPath,
            ':id' => $id
          ]);

          if ($stmt->rowCount() === 0) {
            return $this->error("Контакт не найден или данные не изменились", 404);
          }

          return $this->success(['message' => 'Контакт успешно обновлён']);

        } catch (\Exception $e) {
          error_log("Ошибка обновления контакта: " . $e->getMessage());
          return $this->error("Ошибка обновления контакта", 500);
        }
      }


    // Создание нового контакта
    public function createContact($data, $icon = null)
    {
      try {
        $iconPath = null;

        if($icon) {
          $maxSize = 50 * 1024;
          $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/server/uploads/contact/icons/';

          if ($icon['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Ошибка при загрузке файла: ' . $icon['error']];
          }

          $allowedTypes = ['image/svg+xml'];
          if (!in_array($icon['type'], $allowedTypes)) {
            return ['error' => 'Недопустимый тип файла. Разрешены SVG.'];
          }
          
          if ($icon['size'] > $maxSize) {
            return ['error' => 'Слишком большой файл. Максимальный размер — 50KB.'];
          }

          if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
              return ['error' => 'Не удалось создать директорию для загрузки.'];
            }
          }

          $extension = pathinfo($icon['name'], PATHINFO_EXTENSION);
          $uniqueName = 'icon-' . time() . '.' . $extension;
          $uploadPath = $uploadDir . $uniqueName;
          // Перемещение файла
          if (!move_uploaded_file($icon['tmp_name'], $uploadPath)) {
            return ['error' => 'Не удалось сохранить загруженную иконку.'];
          }

          $iconPath = '/server/uploads/contact/icons/' . $uniqueName;
        }
  
        $query = "INSERT INTO Contacts (type,title,content,link,icon_path) VALUES (?, ?, ?, ?, ?)";
  
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
          $data['type'],
          $data['title'],
          $data['content'],
          $data['link'],
          $iconPath,
        ]);
  
        $contactId = $this->pdo->lastInsertId();
        error_log("Создан элемент контактов ID: " . $contactId);
  
        return $this->success(['contact_id' => $contactId, 'message' => 'Элемент контактов создан'], 201);
      } catch (\Exception $e) {
        error_log("Ошибка создания элемента контактов:" . $e->getMessage());
        return $this->error("Ошибка создания элемента контактов:" . $e->getMessage(), 400);
      }
    }

    //Удаление контакта с бд

    public function deleteContactItem($id)
    {
      try {
        $iconQuery = "SELECT icon_path FROM Contacts WHERE contact_id = :id";
        $iconStmt = $this->pdo->prepare($iconQuery);
        $iconStmt->execute([':id' => $id]);
        $contact = $iconStmt->fetch(\PDO::FETCH_ASSOC);

        $query = "DELETE FROM Contacts WHERE contact_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
          return $this->error("Элемент контактов не найден", 404);
        }

        if ($contact && !empty($contact['icon_path'])) {
          $fullPath = $_SERVER['DOCUMENT_ROOT'] . $contact['icon_path'];
          if (file_exists($fullPath)) {
            unlink($fullPath);
          }
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
  $contentType = $_SERVER['CONTENT_TYPE'];
  $input = [];
  $icon = null;


  if (strpos($contentType, 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      http_response_code(400);
      echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
      exit;
    }
  } elseif (strpos($contentType,'multipart/form-data') !== false) {
    $input = $_POST;

        if (isset($_FILES['icon_path']) && $_FILES['icon_path']['error'] === UPLOAD_ERR_OK) {
          $icon = $_FILES['icon_path'];
      } else {
          $icon = null;
      }
  } else {
    http_response_code(415);
    echo json_encode(['success' => false, 'error' => 'Unsupported Media Type: ' . $contentType]);
    exit;
}


  switch ($method) {
    case 'GET':
      echo $api->getContacts();
      break;

    case 'POST':
      if ($input && strpos($contentType, 'application/json') !== false) {
          echo $api->createContact($input);
        break;
      } elseif ($input && $icon && strpos($contentType,'multipart/form-data') !== false) {
          echo $api->createContact($input, $icon);
        break;
      } elseif ($input && strpos($contentType,'multipart/form-data') !== false) {
         echo $api->createContact($input);
        break;
      } else {
          echo $api->error("Данные не переданы");
        break;
      }
      
      

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
          echo $api->updateContact($id, $input);
      break;
      } elseif ($input && $icon && strpos($contentType,'multipart/form-data') !== false && $id) {
          echo $api->updateContact($id, $input, $icon);
        break;
      } elseif ($input && strpos($contentType,'multipart/form-data') !== false) {
         echo $api->updateContact($id, $input);
        break;
      } else {
          echo $api->error("Данные не переданы");
        break;
      }
    
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

