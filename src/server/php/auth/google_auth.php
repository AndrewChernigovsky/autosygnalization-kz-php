<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// Конфигурация
$clientID = '21540797099-2m4tbtjpgo8vjlaac8ct20jq7aoio5r6.apps.googleusercontent.com';
$clientSecret = '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';
// $redirectUri = 'http://localhost:3000/google-auth-callback.php';
$redirectUri = 'http://localhost:5173/index.html';


// Создаем клиент Google
$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Обработка callback от Google
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token);

  // Получаем информацию о пользователе
  $google_oauth = new Google\Service\Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();

  $email = $google_account_info->email;
  $name = $google_account_info->name;
  $picture = $google_account_info->picture;

  // Здесь вы можете:
  // 1. Проверить есть ли пользователь в вашей БД
  // 2. Если нет - создать новую запись
  // 3. Установить сессию для пользователя

  session_start();
  $_SESSION['user_email'] = $email;
  $_SESSION['user_name'] = $name;
  $_SESSION['user_picture'] = $picture;

  header('Location: /admin');
  exit();
}

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