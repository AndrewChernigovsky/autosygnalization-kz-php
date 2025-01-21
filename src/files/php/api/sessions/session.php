<?php
if (session_status() === PHP_SESSION_NONE) {
  // session_start();
  $session_lifetime = 1200;

  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_lifetime)) {
    session_unset();
    session_destroy();
    exit();
  }

  $_SESSION['last_activity'] = time();

  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }
}
?>