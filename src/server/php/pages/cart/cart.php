<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use LAYOUT\Header;
use LAYOUT\Footer;
use LAYOUT\Head;
use DATA\Products;
use COMPONENTS\ModalForm;

use function AUTH\SESSIONS\initSession;
use function FUNCTIONS\getShop;

initSession();
$header = new Header();
$footer = new Footer();
$modalForm = new ModalForm();
$products = (new Products())->getData();

$title = 'Корзина | Auto Security';
$head = new Head($title, [], []);
?>


<!DOCTYPE html>
<html lang="ru">
<?= $head->setHead();?>

<body>
  <?= $header->getHeader();?>
  <main class="main">
    <section class="cart-section">
      <div class="container">
        <h2>Корзина</h2>
        <div class="cart-section__head">
        </div>
        <div class="cart-section__products">
        </div>
        <p class="cart-section__result">
          <span>Итого: </span>
          <span class="cost-total"></span>
        </p>
        <div class="cart-section__options">
          <a href="<?= "/catalog?SELECT=name&PAGE=1"; ?>" class="button y-button-primary">Вернуться в
            магазин</a>
          <div class="cart-section__option">
            <button type="button" class="button y-button-secondary  y-button-third" id="buy-fast-order">Быстрый
              заказ</button>
            <a href="<?= "$path/server/php/pages/checkout/checkout.php"; ?>"
              class="button y-button-secondary  y-button-third">Оформить
              заказ</a>
          </div>
        </div>


      </div>
    </section>
    <?= getShop("setup"); ?>
    <?= getShop("shop"); ?>
  </main>

  <?= $footer->getFooter();?>
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
  <?= $modalForm->render();?>
</body>

</html>