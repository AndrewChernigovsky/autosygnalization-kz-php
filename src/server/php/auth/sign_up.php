<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/logger.php';

use DATABASE\DataBase;
use function AUTH\log_message;

// Защита от брутфорса - ограничение попыток входа
$max_attempts = 5;
$lockout_time = 300; // 5 минут

if (!isset($_SESSION['login_attempts'])) {
  $_SESSION['login_attempts'] = 0;
  $_SESSION['first_attempt_time'] = time();
}

// Проверяем, не заблокирован ли пользователь
if ($_SESSION['login_attempts'] >= $max_attempts) {
  $time_passed = time() - $_SESSION['first_attempt_time'];
  if ($time_passed < $lockout_time) {
    $remaining_time = $lockout_time - $time_passed;
    $_SESSION['error_message'] = "Слишком много попыток входа. Попробуйте через " . ceil($remaining_time / 60) . " минут.";
    header('Location: /login');
    exit;
  } else {
    // Сбрасываем счетчик после истечения времени
    $_SESSION['login_attempts'] = 0;
    $_SESSION['first_attempt_time'] = time();
  }
}

log_message('sign_up.php 3');

// Заголовки безопасности
header("Access-Control-Allow-Origin: http://localhost:5173, https://starline-service.kz");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

// Дополнительные заголовки безопасности
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

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

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

// Валидация email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['error_message'] = 'Некорректный формат email.';
  $_SESSION['login_attempts']++;
  if ($_SESSION['login_attempts'] >= 3) {
    sleep(2);
  }
  session_write_close();
  header('Location: /login');
  exit;
}

// Очистка пароля от лишних символов
$password = trim($password);

if (empty($email) || empty($password)) {
  http_response_code(400);
  $_SESSION['error_message'] = 'Все поля обязательны для заполнения.';
  $_SESSION['auth_ok'] = false;
  session_write_close();
  header('Location: /login');
  exit;
}

try {
  $dbConnection = DataBase::getConnection();
  $pdo = $dbConnection->getPdo();

  $sql = "SELECT id, password, email FROM users WHERE email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  error_log('sign_up.php1111: ' . print_r($user, true));
  if ($user && password_verify($password, $user['password'])) {
    // Успешный вход - сбрасываем счетчик попыток
    $_SESSION['login_attempts'] = 0;
    $_SESSION['first_attempt_time'] = time();
    $_SESSION['email'] = $user['email'];

    $_SESSION['success_message'] = 'Вы являетесь администратором. Перевожу вас на страницу авторизации Google.';
    $_SESSION['auth_ok'] = true;
    $_SESSION['last_activity'] = time(); // Время последней активности
    // session_write_close();
    header('Location: /login');
    exit;
  } else if (($user && !password_verify($password, $user['password'])) || !$user) {
    $_SESSION['login_attempts']++;
    $_SESSION['error_message'] = 'Неверный логин или пароль.';
    if ($_SESSION['login_attempts'] >= 3) {
      sleep(2);
    }
    header('Location: /login');
    exit;
  } else {
    $_SESSION['login_attempts']++;
    $_SESSION['error_message'] = 'Вы не являетесь администратором. Вход запрещен.';
    if ($_SESSION['login_attempts'] >= 3) {
      sleep(2);
    }
    header('Location: /login');
    exit;
  }

} catch (\Exception $e) {
  error_log("Ошибка " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Ошибка на сервере.']);
}
?>