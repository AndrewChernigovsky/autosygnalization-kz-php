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

// Очищаем все параметры фильтрации
$filterKeys = array_filter(array_keys($_SESSION), function($key) {
    return strpos($key, 'get_params_') === 0;
});

foreach ($filterKeys as $key) {
    unset($_SESSION[$key]);
}

session_destroy();
echo "Session destroyed";
