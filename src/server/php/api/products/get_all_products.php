<?php

namespace API\PRODUCTS;

require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\Products;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

header("Content-Type: application/json; charset=UTF-8");


$productsData = new Products();
$allProducts = $productsData->getData();

$groupedProducts = [];
foreach ($allProducts as $product) {
  if (isset($product['category'])) {
    if (!isset($groupedProducts[$product['category']])) {
      $groupedProducts[$product['category']] = [];
    }
    $groupedProducts[$product['category']][] = $product;
  }
}

echo json_encode(['category' => $groupedProducts]);
