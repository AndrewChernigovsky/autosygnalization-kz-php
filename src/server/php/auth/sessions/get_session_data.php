<?php

namespace AUTH\SESSIONS;

function getSessionData()
{
  if (isset($_SESSION['cart'])) {
    echo json_encode($_SESSION['cart']);
  } else {
    echo json_encode(["message" => "No products in the cart."]);
  }
}

function getSessionDataAdmin()
{
  if (isset($_SESSION['admin_id'])) {
    echo json_encode($_SESSION['admin_id']);
  } else {
    echo json_encode(["message" => "No admin id."]);
  }
}