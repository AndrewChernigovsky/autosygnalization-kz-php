<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../helpers/components/setup.php';
include_once __DIR__ . '/../../helpers/components/product.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$title = 'Оформление заказа | Auto Security';

include_once $head_path;
include_once $docROOT . $path . '/files/php/data/products.php';

$head = new Head($title, [], []);
?>


<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include_once $docROOT . $path . '/files/php/layout/header.php'; ?>
  <main class="main">
    <section class="cart-section">
      <div class="container">
        <h2 class="title__h2">Оформление заказа</h2>
        <div class="cart-section__head checkout">
        </div>
        <div class="cart-section__products checkout">
        </div>
        <p class="cart-section__total">
          <span>Итого: </span>
          <span class="cost-total">
            <?php
            $total = 0;
            echo $total;
            ?>
          </span>
        </p>      
        <form action="" method="post" class="checkout-form">
          <div class="checkout-form__body"></div>
          <div class="checkout-form__footer">
            <a href="" class="link button y-button-third">Продолжить покупки</a>
            <p>
              <span>Итого: </span>
              <span class="quantity-total"></span>
              <span>Товар/ов на сумму: </span>
              <span class="cost-total"></span>
            </p>
            <button type="submit" class="button y-button-primary">Оформить заказ</button>
          </div>
        </form>
      </div>
    </section>
  </main>

  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
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