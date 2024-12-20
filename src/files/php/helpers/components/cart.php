<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';

class Cart
{
  private $path;
  private $variables;
  private $totalQuantity = 0;
  private $products = [];

  public function __construct($products)
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
    $path = $this->variables->getPathFileURL();
    $this->path = $path;
    $this->products[] = $products;
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
        <div class="counter"><?php echo $this->totalQuantity; ?></div>
      </a>
    </div>
    <?php
    return ob_get_clean();
  }
  public function calculateTotalQuantity($id, $quantity)
  {
    $this->totalQuantity = 0;

    foreach ($this->products as &$existingProduct) {
      if ($existingProduct['id'] === $id) {
        $this->totalQuantity += $quantity;
      }
    }

    return $this->totalQuantity;
  }

  public function getProducts()
  {
    return $this->products;
  }
  public function addProduct($id)
  {
    foreach ($this->products as &$product) {
      foreach ($product as $pro) {
        if ($pro['id'] === $id) {
          $pro['quantity'] += 1;
          $this->calculateTotalQuantity($id, 1);
          echo $this->totalQuantity;
          return;
        }
      }
    }
  }
  public function updateProduct($id, $newData)
  {
    foreach ($this->products as &$product) {
      if ($product['id'] === $id) {
        $product = array_merge($product, $newData);
        return true;
      }
    }
    return false;
  }
  public function removeProduct($id)
  {
    foreach ($this->products as $key => $product) {
      if ($product['id'] === $id) {
        unset($this->products[$key]);
        return true;
      }
    }
    return false;
  }
}

?>