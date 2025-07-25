<?php

namespace API\ADMIN;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\DataBase;
use Exception;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit(0);
}

class SertificatesAPI extends DataBase
{
  private const UPLOAD_DIR = '/server/uploads/sertificates/';
  protected $pdo;

  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  public function handleRequest()
  {
    try {
      $method = $_SERVER['REQUEST_METHOD'];

      switch ($method) {
        case 'GET':
          $this->handleGet();
          break;
        case 'POST':
          $this->handlePost();
          break;
        case 'DELETE':
          $this->handleDelete();
          break;
        default:
          $this->error("Метод не поддерживается", 405);
          break;
      }
    } catch (Exception $e) {
      error_log("Критическая ошибка API 'Сертификаты': " . $e->getMessage());
      $this->error('Внутренняя ошибка сервера', 500);
    }
  }

  private function handleGet()
  {
    try {
      $query = "SELECT * FROM Sertificates ORDER BY position ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();
      $this->success($stmt->fetchAll(\PDO::FETCH_ASSOC));
    } catch (Exception $e) {
      error_log("Ошибка получения данных 'Сертификаты': " . $e->getMessage());
      $this->error("Ошибка получения данных", 500);
    }
  }

  private function handlePost()
  {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['action']) && $input['action'] === 'update_positions') {
      $this->updatePositions($input['items']);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'create') {
      $this->createSertificate();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'update') {
      $this->updateSertificate();
    } else {
      $this->error("Неверное действие", 400);
    }
  }

  private function handleDelete()
  {
    if (!isset($_GET['id'])) {
      $this->error("ID сертификата не указан", 400);
      return;
    }
    $id = (int)$_GET['id'];
    $this->deleteSertificate($id);
  }

  private function createSertificate()
  {
    if (!isset($_FILES['image'])) {
        $this->error("Изображение не загружено", 400);
        return;
    }

    $file = $_FILES['image'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $this->error("Ошибка загрузки файла: " . $file['error'], 400);
        return;
    }

    $uploadPath = $_SERVER['DOCUMENT_ROOT'] . self::UPLOAD_DIR;
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = 'sertificate-' . uniqid() . '.' . $extension;
    $filePath = $uploadPath . $uniqueName;
    $webPath = self::UPLOAD_DIR . $uniqueName;

    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        $this->error("Не удалось сохранить файл", 500);
        return;
    }
    
    try {
        $stmt = $this->pdo->prepare("INSERT INTO Sertificates (image_path, position) VALUES (?, (SELECT COALESCE(MAX(position), 0) + 1 FROM Sertificates as temp))");
        $stmt->execute([$webPath]);
        $newId = $this->pdo->lastInsertId();

        $selectStmt = $this->pdo->prepare("SELECT * FROM Sertificates WHERE sertificate_id = ?");
        $selectStmt->execute([$newId]);
        $newSertificate = $selectStmt->fetch(\PDO::FETCH_ASSOC);

        $this->success($newSertificate);
    } catch (Exception $e) {
        unlink($filePath); // Удаляем файл, если запись в БД не удалась
        error_log("Ошибка создания сертификата: " . $e->getMessage());
        $this->error("Ошибка базы данных при создании", 500);
    }
  }

  private function updateSertificate()
  {
      if (!isset($_POST['sertificate_id']) || !isset($_FILES['image'])) {
          $this->error("Отсутствуют необходимые данные для обновления", 400);
          return;
      }

      $id = (int)$_POST['sertificate_id'];
      $file = $_FILES['image'];

      // 1. Находим старую запись
      $stmt = $this->pdo->prepare("SELECT image_path FROM Sertificates WHERE sertificate_id = ?");
      $stmt->execute([$id]);
      $oldSertificate = $stmt->fetch(\PDO::FETCH_ASSOC);

      if (!$oldSertificate) {
          $this->error("Сертификат с ID {$id} не найден", 404);
          return;
      }
      $oldFilePath = $_SERVER['DOCUMENT_ROOT'] . $oldSertificate['image_path'];

      // 2. Загружаем новый файл
      $uploadPath = $_SERVER['DOCUMENT_ROOT'] . self::UPLOAD_DIR;
      $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
      $uniqueName = 'sertificate-' . uniqid() . '.' . $extension;
      $newFilePath = $uploadPath . $uniqueName;
      $newWebPath = self::UPLOAD_DIR . $uniqueName;

      if (!move_uploaded_file($file['tmp_name'], $newFilePath)) {
          $this->error("Не удалось сохранить новый файл", 500);
          return;
      }

      // 3. Обновляем запись в БД
      try {
          $updateStmt = $this->pdo->prepare("UPDATE Sertificates SET image_path = ? WHERE sertificate_id = ?");
          $updateStmt->execute([$newWebPath, $id]);

          // 4. Удаляем старый файл, если обновление БД прошло успешно
          if (file_exists($oldFilePath)) {
              unlink($oldFilePath);
          }

          // Возвращаем обновленные данные
          $selectStmt = $this->pdo->prepare("SELECT * FROM Sertificates WHERE sertificate_id = ?");
          $selectStmt->execute([$id]);
          $updatedSertificate = $selectStmt->fetch(\PDO::FETCH_ASSOC);
          $this->success($updatedSertificate);

      } catch (Exception $e) {
          // Если обновление БД не удалось, удаляем новый загруженный файл
          if (file_exists($newFilePath)) {
              unlink($newFilePath);
          }
          error_log("Ошибка обновления сертификата: " . $e->getMessage());
          $this->error("Ошибка базы данных при обновлении", 500);
      }
  }

  private function deleteSertificate($id)
  {
    try {
      // Получаем путь к файлу перед удалением записи из БД
      $stmt = $this->pdo->prepare("SELECT image_path FROM Sertificates WHERE sertificate_id = ?");
      $stmt->execute([$id]);
      $sertificate = $stmt->fetch(\PDO::FETCH_ASSOC);

      if (!$sertificate) {
          $this->error("Сертификат не найден", 404);
          return;
      }
      
      // Удаляем запись из БД
      $deleteStmt = $this->pdo->prepare("DELETE FROM Sertificates WHERE sertificate_id = ?");
      $deleteStmt->execute([$id]);

      // Удаляем файл
      $filePath = $_SERVER['DOCUMENT_ROOT'] . $sertificate['image_path'];
      if (file_exists($filePath)) {
          unlink($filePath);
      }

      $this->success(['sertificate_id' => $id]);
    } catch (Exception $e) {
      error_log("Ошибка удаления сертификата: " . $e->getMessage());
      $this->error("Ошибка базы данных при удалении", 500);
    }
  }
  
  private function updatePositions($items)
  {
      try {
          $this->pdo->beginTransaction();
          $stmt = $this->pdo->prepare("UPDATE Sertificates SET position = ? WHERE sertificate_id = ?");
          foreach ($items as $item) {
              $stmt->execute([(int)$item['position'], (int)$item['sertificate_id']]);
          }
          $this->pdo->commit();
          $this->success(['message' => 'Порядок обновлен']);
      } catch (Exception $e) {
          $this->pdo->rollBack();
          error_log("Ошибка обновления порядка сертификатов: " . $e->getMessage());
          $this->error("Ошибка базы данных при обновлении порядка", 500);
      }
  }

  // --- Вспомогательные методы для ответа ---

  private function success($data)
  {
      http_response_code(200);
      echo json_encode(['success' => true, 'data' => $data]);
  }

  private function error($message, $code)
  {
      http_response_code($code);
      echo json_encode(['success' => false, 'error' => $message]);
  }
}

$api = new SertificatesAPI();
$api->handleRequest();