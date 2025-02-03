<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../db/createDataBase.php';
include_once __DIR__ . '/../../api/sessions/session.php';

class Cart
{
  private $path;
  private $variables;
  private $pdo;
  private $totalQuantity;
  public function __construct()
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
    $path = $this->variables->getPathFileURL();
    $this->path = $path;
    $this->totalQuantity = $this->getTotalQuantity();

    $db = new CreateDatabase();
    $this->pdo = $db->getConnection();
  }

  public function initCart()
  {
    $icon = $this->path . '/assets/images/vectors/sprite.svg#cart';
    $link = $this->path . '/files/php/pages/cart/cart.php';
    ob_start();
    ?>
    <div class="header__cart cart">
      <a class="link" href='<?php echo $link; ?>'>
        <svg width="50" height="50">
          <use href='<?php echo $icon; ?>'></use>
        </svg>
        <div class="counter">0</div>
      </a>
    </div>
    <?php
    return ob_get_clean();
  }

  public function setQuantity($id)
  {
    if (isset($_SESSION['cart'])) {
      if (isset($_SESSION['cart'][$id]) && isset($_SESSION['cart'][$id]['quantity'])) {
        $quantity = $_SESSION['cart'][$id]['quantity'];
        return $quantity;
      }
    }
    return 0;
  }
  public function getTotalQuantity()
  {
      if (isset($_SESSION['cart'])) {
        $count = count($_SESSION['cart']);
        error_log(print_r($count, true));
        error_log("cart"); // Просто записываем "cart" в лог
        return count($_SESSION['cart']);
      } else {
          return 0;
      }
  }
  // public function getTotalQuantity()
  // {
  //   if (isset($_SESSION['cart'])) {
  //     $products = $_SESSION['cart'];
  //     foreach ($products as $product) {
  //       $this->totalQuantity += $product['quantity'];
  //     }
  //     return $this->totalQuantity;
  //   } else {
  //     return 0;
  //   }
  // }
}

?>