<?php
include_once __DIR__ . '/../../data/products.php';
include_once __DIR__ . '/../../db/createDataBase.php';

$db = new CreateDatabase();

$connection = $db->getConnection();

if (!$connection) {
  error_log("Failed to connect to the database.");
} else {
  error_log("Connected to the database successfully.");
}

// $data = json_decode(file_get_contents("php://input"), true);

// if (isset($data['id'])) {
//   $id = $data['id'];
//   error_log("Received ID: " . $id);

//   foreach ($products as &$product) {
//     error_log("product ID: " . $product['id']);
//     if ($product['id'] == $id) {
//       echo json_encode($product);
//       exit;
//     }
//   }

//   echo json_encode(["message" => "Product not found"]);
// } else {
//   echo json_encode(["message" => "ID not provided"]);
// }
?>