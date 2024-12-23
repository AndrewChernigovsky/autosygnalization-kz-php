<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");

include_once __DIR__ . '/../../data/products.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
  $id = $data['id'];
  error_log("Received ID: " . $id);

  foreach ($products as &$product) {
    error_log("product ID: " . $product['id']);
    if ($product['id'] == $id) {
      $product['quantity'] += 1;
      echo json_encode(["message" => "Quantity updated", "product" => $product]);
      exit;
    }
  }

  echo json_encode(["message" => "Product not found"]);
} else {
  echo json_encode(["message" => "ID not provided"]);
}
?>