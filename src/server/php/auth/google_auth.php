<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// Конфигурация
$clientID = '21540797099-2m4tbtjpgo8vjlaac8ct20jq7aoio5r6.apps.googleusercontent.com';
$clientSecret = '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';
// Корректный Redirect URI, указывающий на обработчик
$redirectUri = 'http://localhost:3000/google_auth_callback';

// Создаем клиент Google
$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
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