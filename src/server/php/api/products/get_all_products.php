<?php

namespace API\PRODUCTS;

require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\Products;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $products = (new Products())->getData();
  header('Content-Type: application/json; charset=utf-8');
  error_log(json_encode($products, JSON_UNESCAPED_UNICODE));
  echo json_encode($products, JSON_UNESCAPED_UNICODE);
} else {
  http_response_code(405);
  echo json_encode(['message' => 'Method Not Allowed']);
}
