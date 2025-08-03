<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use function AUTH\SESSIONS\initSession;
use function AUTH\SESSIONS\getSessionDataAdmin;

initSession();
// $admin_id = getSessionDataAdmin();

// if (!$admin_id) {
//   header('Location: /admin-panel.html');
//   exit();
// }


$title = 'Админ панель | Auto Security';

?>


<!DOCTYPE html>
<html lang="ru">


<body>
  <main class="main">
    <div class="container">
      <h1>Админ панель</h1>
      <a href="/google_auth">Войти через Google</a>
    </div>
  </main>
</body>

</html>