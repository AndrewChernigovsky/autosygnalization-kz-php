<?php
// Отключаем вывод warnings и notices
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-control-allow-headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit(0);
}

// Проверяем авторизацию
function checkAuth()
{
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }

  // Проверяем основные параметры авторизации (обычный логин ИЛИ Google авторизация)
  if (
    (empty($_SESSION['auth_ok']) || !isset($_SESSION['auth_ok']) || $_SESSION['auth_ok'] !== true) &&
    (empty($_SESSION['admin_logged_in']) || !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true)
  ) {
    return [
      'authenticated' => false,
      'message' => 'Не авторизован',
      'redirect' => 'server/php/auth/login.php'
    ];
  }

  // Проверяем время последней активности (30 минут)
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Сессия истекла
    $_SESSION = [];
    session_destroy();
    return [
      'authenticated' => false,
      'message' => 'Сессия истекла',
      'redirect' => '/login?expired=1'
    ];
  }

  // Обновляем время последней активности
  $_SESSION['last_activity'] = time();

  // Определяем тип авторизации и данные пользователя
  $authType = '';
  $username = '';
  $email = '';
  $isAuthenticated = false;

  if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && $_SESSION['user_email'] === $_SESSION['email']) {
    $authType = 'google';
    $isAuthenticated = true;
    $username = $_SESSION['user_name'] ?? 'Admin';
    $email = $_SESSION['user_email'] ?? '';
    error_log('check-auth.php: ' . $_SESSION['user_email'] . ' ' . $_SESSION['email']);

  } else {
    $authType = 'standard';
    $isAuthenticated = false;
    $username = $_SESSION['user_name'] ?? 'Admin';
    $email = $_SESSION['user_email'] ?? '';
    error_log('check-auth1111.php: ' . $_SESSION['user_email'] . ' ' . $_SESSION['email']);
  }

  return [
    'authenticated' => $isAuthenticated,
    'message' => 'Авторизован',
    'auth_type' => $authType,
    'user' => [
      'username' => $username,
      'email' => $email,
      'last_activity' => $_SESSION['last_activity']
    ]
  ];
}

// Обрабатываем запрос
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    $authResult = checkAuth();
    http_response_code($authResult['authenticated'] ? 200 : 401);
    echo json_encode($authResult);
    break;

  case 'POST':
    // Для POST запросов можно добавить дополнительную логику
    $authResult = checkAuth();
    http_response_code($authResult['authenticated'] ? 200 : 401);
    echo json_encode($authResult);
    break;

  default:
    http_response_code(405);
    echo json_encode(['error' => 'Метод не разрешен']);
    break;
}
?>