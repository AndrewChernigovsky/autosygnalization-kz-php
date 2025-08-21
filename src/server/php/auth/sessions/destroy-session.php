<?php
// Простой файл для уничтожения сессии
// Проверяем что это запрос от JavaScript, а не автозагрузка
if (!isset($_SERVER['HTTP_REFERER']) && !isset($_SERVER['HTTP_USER_AGENT'])) {
  // Если нет referer и user-agent - это автозагрузка, НЕ выполняем
  return;
}

error_log("destroy-session.php called from JavaScript");
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
session_destroy();
echo "Session destroyed";
