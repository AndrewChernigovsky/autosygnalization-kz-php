<?php
include_once './../../db/createProducts.php';

$type = isset($_GET['data']) ? $_GET['data'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

$products = new CreateProducts();

switch ($type) {
  case 'create':
    $products->createProducts();
    break;
  case 'add':
    if ($id) {
      $products->addProductById($id);
    }
    break;
  default:
    error_log('Произошла ошибка при операции: ');
    break;
}



?>