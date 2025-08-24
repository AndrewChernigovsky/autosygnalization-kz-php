<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/logger.php';

// Запуск сессии в самом начале файла
use DATABASE\DataBase;
use function AUTH\log_message;

log_message('google-auth-callback.php 4');

session_start();
// Загружаем .env из корня server, безопасно
$primaryEnvDir = dirname(__DIR__, 2);
$secondaryEnvDir = dirname($primaryEnvDir, 2) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'server';
$envDir = is_file($primaryEnvDir . DIRECTORY_SEPARATOR . '.env') ? $primaryEnvDir : (is_file($secondaryEnvDir . DIRECTORY_SEPARATOR . '.env') ? $secondaryEnvDir : $primaryEnvDir);
$envPath = $envDir . DIRECTORY_SEPARATOR . '.env';
error_log('GOOGLE_CALLBACK: primaryEnvDir=' . $primaryEnvDir . '; secondaryEnvDir=' . $secondaryEnvDir . '; usingEnvDir=' . $envDir . '; envExists=' . (file_exists($envPath) ? 'yes' : 'no') . '; readable=' . (is_readable($envPath) ? 'yes' : 'no'));
try {
  $dotenv = Dotenv\Dotenv::createImmutable($envDir);
  $dotenv->safeLoad();
} catch (Throwable $e) {
  error_log('GOOGLE_CALLBACK: Dotenv load error: ' . $e->getMessage());
}

$clientID = $_ENV['GOOGLE_CLIENT_ID'] ?? getenv('GOOGLE_CLIENT_ID') ?: null;
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? getenv('GOOGLE_CLIENT_SECRET') ?: null;

// Фолбэк: прямой парсинг .env
if ((!$clientID || !$clientSecret) && file_exists($envPath) && is_readable($envPath)) {
  $content = @file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
  foreach ($content as $line) {
    if ($line === '' || str_starts_with(trim($line), '#')) {
      continue;
    }
    $pos = strpos($line, '=');
    if ($pos === false) {
      continue;
    }
    $key = trim(substr($line, 0, $pos));
    $val = trim(substr($line, $pos + 1));
    $val = trim($val, "\"' ");
    if ($key === 'GOOGLE_CLIENT_ID' && !$clientID) {
      $clientID = $val;
    }
    if ($key === 'GOOGLE_CLIENT_SECRET' && !$clientSecret) {
      $clientSecret = $val;
    }
  }
}

error_log('GOOGLE_CALLBACK: clientId_present=' . ($clientID ? 'yes' : 'no') . ' (len=' . ($clientID ? strlen($clientID) : 0) . '); clientSecret_present=' . ($clientSecret ? 'yes' : 'no') . ' (len=' . ($clientSecret ? strlen($clientSecret) : 0) . ')');
if (!$clientID || !$clientSecret) {
  http_response_code(500);
  echo 'Ошибка конфигурации: отсутствуют GOOGLE_CLIENT_ID/GOOGLE_CLIENT_SECRET';
  exit;
}


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
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
// Redirect URI должен быть идентичен тому, что используется для генерации ссылки
// $client->setRedirectUri('https://starline-service.kz/google_auth_callback');
$client->setRedirectUri('http://localhost:3000/google_auth_callback');
$client->addScope(['email', 'profile']);


// Удаляем преждевременную авторизацию по неинициализированной переменной

// Проверяем state только если он устанавливался на этапе авторизации
if (!empty($_SESSION['oauth_state'])) {
  if (!isset($_GET['state']) || !hash_equals($_SESSION['oauth_state'], (string) $_GET['state'])) {
    http_response_code(400);
    echo 'Некорректный параметр state';
    exit;
  }
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
    // Сохраняем access_token в сессии для корректного logout/revoke
    if (is_array($token) && isset($token['access_token'])) {
      $_SESSION['google_access_token'] = $token['access_token'];
    }

    // Получаем информацию о пользователе
    $google_oauth = new Google\Service\Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();

    // Исключаем некорректную проверку с неинициализированной переменной

    // Сохраняем данные пользователя в сессию
    $_SESSION['user_email'] = $google_account_info->email;
    $_SESSION['user_name'] = $google_account_info->name;
    $_SESSION['user_picture'] = $google_account_info->picture;

    $dbConnection = DataBase::getConnection();

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $dbConnection->prepare($sql);
    $stmt->execute(['email' => $_SESSION['user_email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['role'] === 'admin' && $user['email'] === $_SESSION['user_email']) {
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

// Если до сюда дошли без обработки кода — перенаправляем на главную
// Если код не получен (прямой доступ к файлу), перенаправляем на главную
header('Location: /');
exit();
