<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/logger.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();

  error_log('GOOGLE_AUTH: revokeToken11222=' . $_SESSION['google_access_token']);
  if (!empty($_SESSION['google_access_token'])) {
    try {
      $revokeToken = $_SESSION['google_access_token'];
      error_log('GOOGLE_AUTH: revokeToken1122233=' . $revokeToken);
      // Безопасное обращение к revoke endpoint
      $ch = curl_init('https://oauth2.googleapis.com/revoke?token=' . urlencode($revokeToken));
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/x-www-form-urlencoded']);
      curl_exec($ch);
      curl_close($ch);
      $_MESSAGE_LOGOUT = 'Вы вышли из аккаунта Google';
    } catch (Throwable $e) {
      error_log('GOOGLE_AUTH: revoke error: ' . $e->getMessage());
    }
  }

  // Чистим сессию
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
  session_destroy();
  session_write_close();
  header('Location: /google_auth');
}