<?php

namespace API\PRODUCTS;

require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\Products;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $products = (new Products())->getData();
    header('Content-Type: application/json');
    echo json_encode($products);
} else {
    echo json_encode(['message' => 'Данные не получены']);
}
