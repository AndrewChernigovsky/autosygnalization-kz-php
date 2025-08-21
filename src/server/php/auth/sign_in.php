<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/logger.php';

use DATABASE\DataBase;
use function AUTH\log_message;

log_message('sign_in.php 8');

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Метод не разрешен.']);
  exit;
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$username = $data['username'] ?? null;
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;
$password_confirm = $data['password_confirm'] ?? null;

if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Все поля обязательны для заполнения.']);
  exit;
}

if ($password !== $password_confirm) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Пароли не совпадают.']);
  exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
  $dbConnection = DataBase::getConnection();
  $pdo = $dbConnection->getPdo();

  $sql = "SELECT id FROM users WHERE username = :username OR email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['username' => $username, 'email' => $email]);

  if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Пользователь с таким логином или email уже существует.']);
    exit;
  }

  $sql = "UPDATE users SET username = :username, email = :email, password = :password WHERE email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password]);

  if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'message' => 'Пользователь успешно зарегистрирован.']);
  } else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Не удалось зарегистрировать пользователя.']);
  }
} catch (\Exception $e) {
  error_log("Ошибка при регистрации: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Ошибка на сервере при регистрации.']);
}
?>