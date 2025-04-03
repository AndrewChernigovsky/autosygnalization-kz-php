<?php

namespace API\PRODUCTS;

session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");

include_once __DIR__ . '/../../data/products.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['category'])) {
    $category = $data['category'];
    error_log("Received category: " . $category);
    $matchingProducts = [];
    foreach ($products as &$product) {
        error_log("product category: " . $product['category']);
        if ($product['category'] == $category) {
            $matchingProducts[] = $product;
        }
    }
    if (!empty($matchingProducts)) {
        echo json_encode($matchingProducts);
    } else {
        echo json_encode(["message" => "нет продуктов с такой категорией"]);
    }
} else {
    echo json_encode(["message" => "category not provided"]);
}
