<?php

namespace AUTH\SESSIONS;

require_once __DIR__ . '/../logger.php';

use function AUTH\log_message;

function getSessionData()
{
  log_message('getSessionData() called');
  if (isset($_SESSION['cart'])) {
    echo json_encode($_SESSION['cart']);
  } else {
    echo json_encode(["message" => "No products in the cart."]);
  }
}

function getSessionDataAdmin()
{
  log_message('getSessionDataAdmin() called');
  if (isset($_SESSION['admin_id'])) {
    echo json_encode($_SESSION['admin_id']);
  } else {
    echo json_encode(["message" => "No admin id."]);
  }
}

