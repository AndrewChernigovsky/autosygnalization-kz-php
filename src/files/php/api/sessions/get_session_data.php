<?php
if (isset($_SESSION['cart'])) {
  echo json_encode($_SESSION['cart']);
} else {
  echo json_encode(["message" => "No products in the cart."]);
}
?>