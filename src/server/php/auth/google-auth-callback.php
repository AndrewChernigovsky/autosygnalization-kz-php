<?php
// Запуск сессии в самом начале файла
use DATABASE\DataBase;

session_start();

require_once __DIR__ . '/../../vendor/autoload.php';


$client = new Google\Client();
$client->setClientId('21540797099-2m4tbtjpgo8vjlaac8ct20jq7aoio5r6.apps.googleusercontent.com');
$client->setClientSecret('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');
// Redirect URI должен быть идентичен тому, что используется для генерации ссылки
$client->setRedirectUri('http://localhost:3000/google_auth_callback');
$client->addScope(["email", "profile"]);

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

// Если код не получен (прямой доступ к файлу), перенаправляем на главную
header('Location: /');
exit();
