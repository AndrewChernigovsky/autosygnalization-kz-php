<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use DATABASE\DataBase;

$error_message = '';
$title = 'Админ панель | Auto Security';

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Проверяем, что логин и пароль были переданы
  if (!empty($_POST['login']) && !empty($_POST['password'])) {
    try {
      $dbConnection = DataBase::getConnection();
      $pdo = $dbConnection->getPdo();

      // Ищем пользователя по логину, полученному из формы
      $sql = "SELECT * FROM users WHERE login = :login";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['login' => $_POST['login']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Проверяем, найден ли пользователь и совпадает ли пароль
      // ВАЖНО: В реальном приложении используйте password_verify()
      if ($user && $user['password'] == $_POST['password']) {
        // Если аутентификация успешна, перенаправляем
        header('Location: /google_auth');
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

<body>
  <main class="main">
    <div class="container">
      <h1>Админ панель</h1>
      <form action="/google_auth" method="post">
        <label for="password">Введите пароль</label>
        <input type="password" id="password" name="password" placeholder="Пароль">
        <label for="login">Введите логин</label>
        <input type="text" id="login" name="username" placeholder="Логин">
        <label for="email">Введите email</label>
        <input type="email" id="email" name="email" placeholder="Email">
        <button id="loginButton">Войти</button>
      </form>
      <?php
      // Выводим сообщение об ошибке, если оно есть
      if (!empty($error_message)) {
        echo $error_message;
      }
      ?>
    </div>
  </main>
</body>

</html>