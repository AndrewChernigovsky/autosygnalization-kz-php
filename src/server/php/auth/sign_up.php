<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use DATABASE\DataBase;

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

$data = [];
$contentType = trim($_SERVER["CONTENT_TYPE"] ?? '');

if (strpos($contentType, 'application/json') !== false) {
  $json_data = file_get_contents('php://input');
  $data = json_decode($json_data, true) ?: [];
} else {
  $data = $_POST;
}

$email = $data['email'] ?? null;
$password = $data['password'] ?? null;

if (empty($email) || empty($password)) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Все поля обязательны для заполнения.']);
  exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
  $dbConnection = DataBase::getConnection();
  $pdo = $dbConnection->getPdo();

  $sql = "SELECT id FROM users WHERE email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['email' => $email]);

  if ($stmt->fetch()) {
    $_SESSION['success_message'] = 'Вы являетесь администратором. Перевожу вас на страницу авторизации Google.';
    $_SESSION['auth_ok'] = true;
    session_write_close();
    header('Location: /login');
    exit;
  } else {
    $_SESSION['error_message'] = 'Вы не являетесь администратором. Вход запрещен.';
    header('Location: /login');
    exit;
  }

} catch (\Exception $e) {
  error_log("Ошибка " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Ошибка на сервере.']);
}
?>