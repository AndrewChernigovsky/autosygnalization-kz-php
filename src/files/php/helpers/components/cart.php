<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../db/createDataBase.php';
include_once __DIR__ . '/../../api/sessions/session.php';

class Cart
{
  private $path;
  private $variables;
  private $pdo;
  private $totalQuantity = 0;
  public function __construct()
  {
    $this->variables = new SetVariables();
    $this->variables->setVar();
    $path = $this->variables->getPathFileURL();
    $this->path = $path;

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
        <div class="counter"><?php echo $this->getQuantity(); ?></div>
      </a>
    </div>
    <?php
    return ob_get_clean();
  }

  public function getQuantity()
  {
    if (isset($_SESSION['cart'])) {
      if (isset($_SESSION['cart'][2]) && isset($_SESSION['cart'][2]['quantity'])) {
        $quantity = $_SESSION['cart'][2]['quantity'];
        return $quantity;
      }
    }
    return 0;
  }
}

?>