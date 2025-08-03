<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$client = new Google\Client();
$client->setClientId('21540797099-2m4tbtjpgo8vjlaac8ct20jq7aoio5r6.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/google-auth-callback.php');
$client->addScope(["email", "profile"]);

if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token);

  $google_oauth = new Google\Service\Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();

  session_start();
  $_SESSION['user_email'] = $google_account_info->email;
  $_SESSION['user_name'] = $google_account_info->name;
  $_SESSION['user_picture'] = $google_account_info->picture;

  header('Location: /admin');
  exit();
}

// Если код не получен (прямой доступ к файлу)
header('Location: /');
exit();