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
}
?>