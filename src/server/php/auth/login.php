<?php
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/logger.php';

use DATABASE\DataBase;
use LAYOUT\Head;
use LAYOUT\Header;
use LAYOUT\Footer;
use function AUTH\log_message;

log_message('login.php 1');

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

$head = new Head();
$header = new Header();
$footer = new Footer();

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
        // session_write_close();
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
  .container {
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
  }

  form {
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
  }

  .form-group label {
    font-weight: 500;
    text-align: left;
    font-size: 14px;
  }

  .form-group input {
    padding: 12px 16px;
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8f9fa;
  }

  .form-group input:focus {
    outline: none;
    border-color: #667eea;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  button {
    background: linear-gradient(#280000, #ff0000);
    width: 260px;
    min-height: 40px;
    border-radius: 5px;
    border: 1px solid white;
    color: white;
    justify-content: center;
    align-items: center;
    font-weight: 700;
    font-size: 20px;
    text-transform: uppercase;
  }

  button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    cursor: pointer;
  }

  button:active {
    transform: translateY(0);
  }

  .error {
    background: #fee;
    border: 1px solid #fcc;
    color: #b00020;
    padding: 12px;
    border-radius: 8px;
    margin-top: 20px;
    font-size: 14px;
  }

  .success {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    color: #008000;
    padding: 16px;
    border-radius: 8px;
    margin-top: 20px;
    font-size: 14px;
  }

  .auth-title {
    font-size: 24px;
    font-weight: 700;
    text-align: center;
  }
</style>

<head>
  <?= $head->setHead(); ?>
  <title>Войдите через Admin Panel</title>
  <style>
    .main {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 16px;
      height: 100%;
    }
  </style>
</head>

<body>
  <?= (new Header())->getHeader() ?>
  <main class="main">
    <div class="container">
      <h1 class="auth-title">Авторизация</h1>
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
  <?= (new Footer())->getFooter() ?>
</body>

</html>