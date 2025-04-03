<?php

namespace COMPONENTS;

use function AUTH\SESSIONS\initSession;

class Cart
{
    private $pdo;
    private $totalQuantity;
    public function __construct()
    {
        $this->totalQuantity = $this->getTotalQuantity();
        initSession();
    }

    public function initCart()
    {
        $icon = '/client/images/vectors/sprite.svg#cart';
        $link = "/cart";
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
}

?>