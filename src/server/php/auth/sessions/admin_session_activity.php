<?php

namespace AUTH\SESSIONS;

function adminSessionActivity()
{
  $session_lifetime = 1200;

  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_lifetime)) {
    session_unset();
    session_destroy();
    header('Location: /login');
    exit();
  }

  $_SESSION['last_activity'] = time();
}


?>