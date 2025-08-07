<?php
// Запуск сессии в самом начале файла
use DATABASE\DataBase;

session_start();
$dotenv = Dotenv\Dotenv::createImmutable('./.env');
$dotenv->load();

require_once __DIR__ . '/../../vendor/autoload.php';

$_SESSION['last_activity'] = time();
if (time() - $_SESSION['last_activity'] > 1800) {
  session_destroy();
}

// session_set_cookie_params([
//   'lifetime' => 3600,
//   'path' => '/',
//   'domain' => 'ваш-сайт.ру',
//   'secure' => true, // Только HTTPS
//   'httponly' => true, // Защита от XSS
//   'samesite' => 'Strict' // Защита от CSRF
// ]);

$client = new Google\Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
// Redirect URI должен быть идентичен тому, что используется для генерации ссылки
$client->setRedirectUri('http://localhost:3000/google_auth_callback');
$client->addScope(["email", "profile"]);


if ($user) {
  $_SESSION['admin_logged_in'] = true;
  header('Location: /admin');
  exit();
}

$_SESSION['oauth_state'] = bin2hex(random_bytes(16));
$client->setState($_SESSION['oauth_state']);

if ($_GET['state'] !== $_SESSION['oauth_state']) {
  die("Возможная CSRF-атака!");
}
// Проверяем, получен ли код от Google
if (isset($_GET['code'])) {
  try {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // Проверка на ошибки при получении токена
    if (isset($token['error'])) {
      error_log('Google Access Token Error: ' . $token['error_description']);
      header('Location: /'); // Перенаправление в случае ошибки
      exit();
    }

    $client->setAccessToken($token);

    // Получаем информацию о пользователе
    $google_oauth = new Google\Service\Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();

    if (isset($user_email) === $google_account_info->email) {
      echo "Вы не админ, вы не можете войти в админ панель.";
      exit();
    }

    // Сохраняем данные пользователя в сессию
    $_SESSION['user_email'] = $google_account_info->email;
    $_SESSION['user_name'] = $google_account_info->name;
    $_SESSION['user_picture'] = $google_account_info->picture;

    $dbConnection = DataBase::getConnection();

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $dbConnection->prepare($sql);
    $stmt->execute(['email' => $_SESSION['user_email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['role'] == 'admin' && $user['email'] == $_SESSION['user_email']) {
      session_regenerate_id(true);
      $_SESSION['admin_logged_in'] = true;
      header('Location: /admin');
      exit();
    } else {
      header('Location: /');
      exit();
    }
  } catch (Exception $e) {
    // Логируем исключения
    error_log('Google Auth Exception: ' . $e->getMessage());
    header('Location: /'); // Перенаправление в случае ошибки
    exit();
  }
}

if (!$user) {
  error_log("Попытка неавторизованного доступа в админ-панель: " . $google_account_info->email);
  header('Location: /'); // Редирект можно закомментировать для отладки
  echo "Доступ запрещен. Вы не являетесь администратором.";
  exit();
}
// Если код не получен (прямой доступ к файлу), перенаправляем на главную
header('Location: /');
exit();
