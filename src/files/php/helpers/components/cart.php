<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

class Cart
{
  private $path;
  private $variables;
  public function __construct()
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
    $path = $this->variables->getDocRoot() . $this->variables->getPathFileURL();
    $this->path = $path;
  }

  public function initCart()
  {
    ob_start();
    ?>
    <div class="header__cart cart">
      <a class="link" href="<?php echo $path . '/files/php/pages/cart/cart.php' ?>">
        <svg width="50" height="50">
          <use href="<?php echo $path . '/assets/images/vectors/sprite.svg#cart' ?>"></use>
        </svg>
        <div class="counter"><?php echo htmlspecialchars($id); ?></div>
      </a>
    </div>
    <?php
    return ob_get_clean();
  }

  public function addProduct($productID, $quantity)
  {

  }
}

?>