<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;
use COMPONENTS\ModalForm;

use function AUTH\SESSIONS\initSession;
use function FUNCTIONS\getShop;
use function FUNCTIONS\renderPhoneButton;

initSession();


$title = 'Оформление заказа | Auto Security';
$head = new Head($title, [], []);
$header = new Header();
$footer = new Footer();

// $products = (new Products())->getData();
?>


<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?= $header->getHeader(); ?>
  <main class="main">
    <section class="cart-section">
      <div class="container">
        <h2 class="title__h2">Оформление заказа</h2>
        <div class="cart-section__head checkout">
        </div>
        <ol class="cart-section__products list-style-none checkout">
        </ol>
        <p class="cart-section__total">
          <span>Итого: </span>
          <span class="cost-total">
            <?= 0; ?>
          </span>
        </p>
        <form action="" method="post" class="checkout-form">
          <div class="checkout-form__body"></div>
          <div class="checkout-form__footer">
            <a href="<?= "/catalog?SELECT=name&PAGE=1" ?>" class="link button y-button-third">Продолжить покупки</a>
            <p>
              <span>Итого: </span>
              <span class="quantity-total"></span>
              <span>Товар/ов на сумму: </span>
              <span class="cost-total"></span>
            </p>
            <button type="submit" class="button y-button-primary" id="checkout-button">Оформить заказ</button>
          </div>
        </form>
      </div>
    </section>
  </main>
  <?= getShop('setup'); ?>
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?= renderPhoneButton(); ?>
  <template id="product-template">
    <article class='product-card' id="">
      <div class="product-card__bg">
        <img src="" alt="" width="300" height="250">
      </div>
      <div class="product-card__body">
        <div class="product-card__head">
          <h3></h3>
          <p class="price"></p>
        </div>
        <div class="product-card__buttons">
          <a class="button y-button-secondary" href="">Подробнее</a>
          <button type="button" class="button y-button-primary cart-button" data-id="">Купить</button>
        </div>
        <p class="quantity"></p>
      </div>
    </article>
  </template>
</body>

</html>