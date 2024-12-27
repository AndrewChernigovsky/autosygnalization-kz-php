<?php
include_once './../../db/createProducts.php';
include_once './../../data/products.php';

$type = isset($_GET['data']) ? $_GET['data'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

$productsInit = new CreateProducts();

switch ($type) {
  case 'create':
    $productsInit->createProducts();
    break;
  case 'add':
    if ($id) {
      $productsInit->addProductById($id);
    }
    break;
  case 'quantity':
    $productsInit->getQuantity();
    break;
  case 'cart':
    $productsInit->getCart($products);
    break;
  default:
    error_log('Произошла ошибка при операции: ');
    break;
}



?>