<?php
include_once './../../db/createProducts.php';
include_once './../../data/products.php';

$type = isset($_GET['data']) ? $_GET['data'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

$productsInit = new CreateProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $type === 'cart') {
  $json = file_get_contents('php://input');
  $products = json_decode($json, true); // Декодируем JSON в массив

  if ($products) {
    $data = $productsInit->getCart($products);
    $response = [
      'message' => 'Корзина успешно обновлена',
      'data' => $data
    ];
    echo json_encode($response);
  } else {
    echo json_encode(['error' => 'Invalid cart data']);
  }
  exit;
}

if (isset($type)) {
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
} else {
  echo json_decode("message: параметр data не задан");
}


?>