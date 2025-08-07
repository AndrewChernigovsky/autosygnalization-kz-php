<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// Корректный Redirect URI, указывающий на обработчик
$redirectUri = 'http://localhost:3000/google_auth_callback';

$dotenv = Dotenv\Dotenv::createImmutable('./.env');
$dotenv->load();

require_once __DIR__ . '/../../vendor/autoload.php';

// Создаем клиент Google
$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

// Конфигурация
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);

$client->addScope("email");
$client->addScope("profile");

// Этот файл только генерирует ссылку для входа.
// Логика обработки ответа находится в google-auth-callback.php

// Ссылка для авторизации
$authUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login with Google</title>
</head>

<body>
  <h2>Login with Google</h2>
  <a href="<?= htmlspecialchars($authUrl) ?>">Sign in with Google</a>
</body>

</html>