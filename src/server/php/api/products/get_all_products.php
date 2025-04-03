<?php

namespace API\PRODUCTS;

session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");

include_once __DIR__ . '/../../data/products.php';

if (isset($products)) {
    echo json_encode($products);
    exit;
}
