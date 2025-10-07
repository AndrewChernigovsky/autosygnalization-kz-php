<?php
session_start();
$hasToken = !empty($_SESSION['google_access_token']);
$hasAuth = !empty($_SESSION['auth_ok']);
error_log('ADMIN session: hasToken=' . ($hasToken ? '1' : '0') . '; hasAuth=' . ($hasAuth ? '1' : '0'));
if (!$hasToken || !$hasAuth) {
  $_SESSION['error_message'] = 'Вы не авторизованы. Перевожу вас на страницу авторизации.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="/vite.svg">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vite + Vue + TS</title>
  <link rel="stylesheet" crossorigin href="/admin/assets/index-CHXsUSLN.css">
</head>

<body class="my-page">
  <?php if (!empty($_SESSION['error_message'])): ?>
    <div class="error" style="color: #b00020; margin: 16px;"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
    <div style="margin: 16px;">Перенаправление через <span id="countdown">3</span> сек.</div>
    <script>
      (function () {
        var seconds = 3;
        var el = document.getElementById('countdown');
        if (el) {
          el.textContent = seconds;
          var timer = setInterval(function () {
            seconds -= 1;
            el.textContent = seconds;
            if (seconds <= 0) {
              clearInterval(timer);
              window.location.href = '/login';
            }
          }, 1000);
        }
      })();
    </script>
    <?php unset($_SESSION['error_message']); ?>
  <?php else: ?>
    <?php
    // В продакшене отдаем собранное приложение из dist/
    $distIndexPath = __DIR__ . '/dist/index.html';
    if (file_exists($distIndexPath)) {
      // Читаем содержимое dist/index.html
      $distContent = file_get_contents($distIndexPath);
      // Заменяем относительные пути на абсолютные от /admin/
      $distContent = str_replace('href="/', 'href="/admin/', $distContent);
      $distContent = str_replace('src="/', 'src="/admin/', $distContent);
      echo $distContent;
    } else {
      // Fallback для локальной разработки (Vite dev server)
      echo '<div id="app"></div>';
      echo ' <script type="module" crossorigin src="/admin/assets/index-BCa4Ynfu.js"></script>';
    }
    ?>
  <?php endif; ?>
</body>

</html>