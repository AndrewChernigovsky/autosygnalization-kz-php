<?php
include_once 'createDataBase.php';
include_once __DIR__ . '/../api/sessions/session.php';
class CreateProducts extends CreateDatabase
{
  public function createProducts()
  {

    $connection = $this->getConnection();

    $sql = "
    CREATE TABLE IF NOT EXISTS products (
        id  VARCHAR(255) PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        quantity INT NOT NULL
    )
";

    try {
      $connection->exec($sql);
      error_log("Table 'products' created successfully.");
    } catch (PDOException $e) {
      error_log("Error creating table: " . $e->getMessage());
    }

  }
  public function addProductById($id)
  {
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }
    error_log("Adding product ID: " . $id);
    if (isset($_SESSION['cart'][$id])) {
      $_SESSION['cart'][$id]['quantity'] += 1;
      error_log("Increased quantity for product ID: " . $id);
    } else {
      $_SESSION['cart'][$id] = [
        'id' => $id,
        'quantity' => 1,
      ];
      error_log("Added new product ID: " . $id);
    }
    error_log('Cart contents: ' . print_r($_SESSION['cart'], true));
  }

  public function getQuantity()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['data'] === 'quantity') {

      $totalQuantity = 0;

      if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
          $totalQuantity += $item['quantity'];
        }

        echo json_encode($totalQuantity);
      }
    }
  }

  public function getCart($products)
  {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    error_log(print_r($data, true) . ' :DATA ');

    if ($data) {
      $filteredProducts = array_filter($products, function ($product) use ($data) {
        return in_array($product['id'], array_column($data, 'id'));
      });
      error_log(print_r($filteredProducts, true) . ' :FILTERED PRODUCTS ');

      $updatedProducts = array_map(function ($product) use ($data) {
        $matchingProduct = current(array_filter($data, function ($item) use ($product) {
          return $item['id'] === $product['id'];
        }));

        if ($matchingProduct) {
          $product['quantity'] = intval($matchingProduct['quantity']);
        }

        return $product;
      }, $filteredProducts);
      error_log(print_r($updatedProducts, true) . ' :UPDATED PRODUCTS ');
      $_SESSION['cart'] = $updatedProducts;
      error_log(print_r($_SESSION['cart'], true) . ' : SESSION_DATA ');

      return json_encode(array_values($updatedProducts));
    } else {
      return json_encode(["message" => "No data received."]);
    }
  }
}
?>