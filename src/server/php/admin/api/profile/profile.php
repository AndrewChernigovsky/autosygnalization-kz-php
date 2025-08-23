<?php
// Отключаем вывод warnings и notices
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../../../../vendor/autoload.php';

use DATABASE\DataBase;

// Запускаем сессию если не активна
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

error_log('ProfileAPI: session_status=' . session_status() . '; session_id=' . session_id());

// Проверяем авторизацию
if (
  (empty($_SESSION['auth_ok']) || !isset($_SESSION['auth_ok']) || $_SESSION['auth_ok'] !== true) &&
  (empty($_SESSION['admin_logged_in']) || !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true)
) {
  error_log('ProfileAPI: Not authorized - auth_ok=' . ($_SESSION['auth_ok'] ?? 'empty') . '; admin_logged_in=' . ($_SESSION['admin_logged_in'] ?? 'empty'));
  http_response_code(401);
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'error' => 'Не авторизован', 'redirect' => '/login']);
  exit;
}

// Проверяем время последней активности (30 минут)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
  error_log('ProfileAPI: Session expired');
  $_SESSION = [];
  session_destroy();
  http_response_code(401);
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'error' => 'Сессия истекла', 'redirect' => '/login?expired=1']);
  exit;
}

// Обновляем время последней активности
$_SESSION['last_activity'] = time();

error_log('ProfileAPI: Authorization successful - auth_ok=' . ($_SESSION['auth_ok'] ?? 'empty') . '; admin_logged_in=' . ($_SESSION['admin_logged_in'] ?? 'empty'));

// Базовое логирование в самом начале
$log_file = sys_get_temp_dir() . '/profile-debug.log';
file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Script started\n", FILE_APPEND);

try {
  header("Access-Control-Allow-Origin: http://localhost:5173");
  header("Access-Control-Allow-Methods: GET, POST");
  header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
  header('Content-Type: application/json');

  file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Headers set\n", FILE_APPEND);
} catch (Exception $e) {
  file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Header error: " . $e->getMessage() . "\n", FILE_APPEND);
}

file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Before require autoload\n", FILE_APPEND);
file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Autoload loaded\n", FILE_APPEND);
file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Classes imported\n", FILE_APPEND);

function log_message($message)
{
  global $log_file;
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($log_file, "[$timestamp] " . $message . "\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  log_message('OPTIONS request received');
  exit(0);
}

class ProfileAPI extends DataBase
{
  protected $pdo;

  public function __construct()
  {
    log_message('ProfileAPI: Constructor started');
    try {
      $db = DataBase::getInstance();
      $this->pdo = $db->getPdo();
      log_message('ProfileAPI: Constructor completed successfully');
    } catch (Exception $e) {
      log_message('ProfileAPI: Constructor error: ' . $e->getMessage());
      throw $e;
    }
  }

  public function getProfile()
  {
    try {
      log_message('ProfileAPI: getProfile started');

      // Получаем профиль единственного пользователя (ID = 1)
      $sql = "SELECT id, username, email FROM users WHERE id = 1";
      log_message('ProfileAPI: SQL query: ' . $sql);

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      log_message('ProfileAPI: User data: ' . print_r($user, true));

      if (!$user) {
        log_message('ProfileAPI: User not found');
        return $this->error('Пользователь не найден', 404);
      }

      log_message('ProfileAPI: getProfile completed successfully');
      return $this->success($user);
    } catch (Exception $e) {
      log_message('ProfileAPI: getProfile error: ' . $e->getMessage());
      return $this->error($e->getMessage());
    }
  }

  public function editProfile($data)
  {
    try {
      // Проверяем обязательные поля
      if (empty($data['username']) || empty($data['password'])) {
        return $this->error('Пустые поля не допустимы');
      }


      $sql = "UPDATE users SET username = :username, password = :password WHERE id = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        'username' => $data['username'],
        'password' => $data['password']
      ]);

      // Обновляем пароль только если он передан
      if (!empty($data['password'])) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $passwordSql = "UPDATE users SET password = :password WHERE id = 1";
        $passwordStmt = $this->pdo->prepare($passwordSql);
        $passwordStmt->execute(['password' => $hashedPassword]);
      }

      return $this->success('Профиль успешно обновлен');
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  public function handleRequest()
  {
    $method = $_SERVER['REQUEST_METHOD'];
    switch ($method) {
      case 'GET':
        return $this->getProfile();
      case 'POST':
        return $this->editProfile($_POST);
      default:
        return $this->error('Неверный метод запроса');
    }
  }

  private function success($data, $statusCode = 200)
  {
    http_response_code($statusCode);
    return json_encode(['success' => true, 'data' => $data]);
  }

  public function error($message, $statusCode = 400)
  {
    http_response_code($statusCode);
    return json_encode(['success' => false, 'error' => $message]);
  }
}

file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Before creating API instance\n", FILE_APPEND);

$api = new ProfileAPI();

file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] API instance created\n", FILE_APPEND);

echo $api->handleRequest();

file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] Script completed\n", FILE_APPEND);

