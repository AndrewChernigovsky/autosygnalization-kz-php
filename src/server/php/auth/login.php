<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use DATABASE\DataBase;

$error_message = '';
if (!empty($_SESSION['error_message'])) {
  $error_message = $_SESSION['error_message'];
  unset($_SESSION['error_message']);
}
$success_message = '';
if (!empty($_SESSION['success_message'])) {
  $success_message = $_SESSION['success_message'];
  unset($_SESSION['success_message']);
}
$title = 'Админ панель | Auto Security';

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Проверяем, что логин и пароль были переданы
  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    try {
      $dbConnection = DataBase::getConnection();
      $pdo = $dbConnection->getPdo();

      // Ищем пользователя по логину, полученному из формы
      $sql = "SELECT * FROM users WHERE email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['email' => $_POST['email']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Проверяем, найден ли пользователь и совпадает ли пароль
      // ВАЖНО: В реальном приложении используйте password_verify()
      if ($user && password_verify($_POST['password'], $user['password'])) {
        // Показываем сообщение и выполняем отложенный редирект через /login
        $_SESSION['auth_ok'] = true;
        error_log('LOGIN_OK: sid=' . session_id() . '; set auth_ok=1');
        $_SESSION['success_message'] = 'Вы являетесь администратором. Перевожу вас на страницу авторизации Google.';
        session_write_close();
        header('Location: /login');
        exit();
      } else {
        $error_message = '<p>Неверный пароль или логин</p>';
      }
    } catch (Exception $e) {
      error_log('Login Error: ' . $e->getMessage());
      $error_message = '<p>Произошла ошибка на сервере.</p>';
    }
  } else {
    $error_message = '<p>Необходимо ввести логин и пароль.</p>';
  }
}
?>
<!DOCTYPE html>
<html lang="ru">
<style>
  .form-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 300px;
  }
</style>

<body>
  <main class="main">
    <div class="container">
      <h1>Админ панель</h1>
      <form action="/sign_up" method="post">
        <div class="form-group">
          <label for="email">Введите email</label>
          <input type="text" id="email" name="email" placeholder="Email">
          <label for="password">Введите пароль</label>
          <input type="password" id="password" name="password" placeholder="Пароль">
        </div>
        <button id="loginButton">Войти</button>
      </form>
      <?php if (!empty($error_message)): ?>
        <div class="error" style="color: #b00020; margin-top: 12px;"><?= htmlspecialchars($error_message) ?></div>
      <?php endif; ?>
      <?php if (!empty($success_message)): ?>
        <div class="success" style="color: #008000; margin-top: 12px;">
          <?= htmlspecialchars($success_message) ?>
          <div style="margin-top:8px;">Перенаправление через <span id="countdown">3</span> сек.</div>
        </div>
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
                  window.location.href = '/google_auth';
                }
              }, 1000);
            }
          })();
        </script>
      <?php endif; ?>
    </div>
  </main>
</body>

</html>