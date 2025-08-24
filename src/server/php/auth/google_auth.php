<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/logger.php';

use LAYOUT\Head;
use LAYOUT\Header;
use LAYOUT\Footer;
use function AUTH\log_message;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
// header('Content-Type: application/json');

log_message('google_auth.php 5');

$title = 'Главная | Auto Security';
$head = new Head($title, [], []);
// Корректный Redirect URI, указывающий на обработчик
// $redirectUri = 'https://starline-service.kz/google_auth_callback';
$redirectUri = 'http://localhost:3000/google_auth_callback';

// Загружаем .env из корня server, безопасно (без исключений, если файла нет)
$primaryEnvDir = dirname(__DIR__, 2); // при запуске из dist → .../dist/server, при запуске из src → .../src/server
$secondaryEnvDir = dirname($primaryEnvDir, 2) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'server'; // резерв на случай запуска из dist
$envDir = is_file($primaryEnvDir . DIRECTORY_SEPARATOR . '.env') ? $primaryEnvDir : (is_file($secondaryEnvDir . DIRECTORY_SEPARATOR . '.env') ? $secondaryEnvDir : $primaryEnvDir);
$envPath = $envDir . DIRECTORY_SEPARATOR . '.env';
error_log('GOOGLE_AUTH: primaryEnvDir=' . $primaryEnvDir . '; secondaryEnvDir=' . $secondaryEnvDir . '; usingEnvDir=' . $envDir . '; envExists=' . (file_exists($envPath) ? 'yes' : 'no') . '; readable=' . (is_readable($envPath) ? 'yes' : 'no'));
try {
  $dotenv = Dotenv\Dotenv::createImmutable($envDir);
  $dotenv->safeLoad();
} catch (Throwable $e) {
  error_log('GOOGLE_AUTH: Dotenv load error: ' . $e->getMessage());
}

$clientID = $_ENV['GOOGLE_CLIENT_ID'] ?? getenv('GOOGLE_CLIENT_ID') ?: null;
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? getenv('GOOGLE_CLIENT_SECRET') ?: null;

// Фолбэк: прямой парсинг .env, если Dotenv не заполнил переменные
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

error_log('GOOGLE_AUTH: clientId_present=' . ($clientID ? 'yes' : 'no') . ' (len=' . ($clientID ? strlen($clientID) : 0) . '); clientSecret_present=' . ($clientSecret ? 'yes' : 'no') . ' (len=' . ($clientSecret ? strlen($clientSecret) : 0) . ')');

if (!$clientID || !$clientSecret) {
  http_response_code(500);
  echo 'Ошибка конфигурации: отсутствуют GOOGLE_CLIENT_ID/GOOGLE_CLIENT_SECRET';
  exit;
}

// Создаем клиент Google
$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('email');
$client->addScope('profile');

// Этот файл только генерирует ссылку для входа.
// Логика обработки ответа находится в google-auth-callback.php

// Ссылка для авторизации
$authUrl = $client->createAuthUrl();
$logoutUrl = '/logout';
// Проверка предварительной авторизации через login/sign_up
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Дополнительная проверка безопасности
if (empty($_SESSION['auth_ok']) || !isset($_SESSION['auth_ok']) || $_SESSION['auth_ok'] !== true) {
  // Логируем попытку несанкционированного доступа
  error_log('GOOGLE_AUTH_SECURITY: Unauthorized access attempt from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));

  // Очищаем сессию для безопасности
  $_SESSION = [];
  session_destroy();

  header('Location: /login');
  exit;
}

// Проверяем время последней активности (30 минут)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
  $_SESSION = [];
  session_destroy();
  header('Location: /login?expired=1');
  exit;
}

// Обновляем время последней активности
$_SESSION['last_activity'] = time();
$_MESSAGE_LOGOUT = '';

error_log('GOOGLE_AUTH_VIEW: sid=' . session_id() . '; auth_ok=' . (!empty($_SESSION['auth_ok']) ? '1' : '0') . '; token=' . (!empty($_SESSION['google_access_token']) ? '1' : '0'));
// Обработчик выхода

?>

<!DOCTYPE html>
<html>

<head>
  <?= $head->setHead(); ?>
  <title>Войдите через Google</title>
  <style>
    .main {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 16px;
      height: 100%;
    }

    .buttons {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 16px;
      margin: 40px;
    }
  </style>
</head>

<body>
  <?= (new Header())->getHeader() ?>
  <main class="main">
    <div class="buttons">
      <a class="y-button button link" href="<?= htmlspecialchars($authUrl) ?>">Войти через Google</a>
      <?php
      if (!empty($_SESSION['google_access_token'])) {
        echo '<a class="x-button button link" href="' . htmlspecialchars($logoutUrl) . '">Выйти из аккаунта</a>';
      }
      ?>
      <p><?= htmlspecialchars($_MESSAGE_LOGOUT) ?></p>
    </div>
  </main>
  <?= (new Footer())->getFooter() ?>
</body>

</html>