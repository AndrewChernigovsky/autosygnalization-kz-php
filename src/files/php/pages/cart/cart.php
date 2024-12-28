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
$title = 'Корзина | Auto Security';

include_once $head_path;

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
        <h2>Корзина</h2>
        <div class="cart-section__head">
          <p><span id="cart-count"><?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span> товара/ов в
            корзине</p>
          <button type="button" id="reset-cart" class="button y-button-secondary">очистить корзину</button>
        </div>
        <?php
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
          ?>
          <div class="cart-section__products">
            <?php echo getProductCardWModel($_SESSION['cart'], true); ?>
          </div>
          <p>
            <span>Итого: </span>
            <span>
              <?php
              $total = 0;
              foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
              }
              echo $total;
              ?>
            </span>
            <span>₸</span>
          </p>
          <a href="<?= "$path/files/php/pages/catalog/catalog.php"; ?>" class="button y-button-primary">Вернуться в
            магазин</a>
          <?php
        } else {
          ?>
          <p>В корзине нет товаров</p>
          <a href="<?= "$path/files/php/pages/catalog/catalog.php"; ?>" class="button y-button-primary">Вернуться в
            магазин</a>
          <?php
        }
        ?>
      </div>
    </section>
    <?= getShop('setup'); ?>
    <?= getShop('shop'); ?>
  </main>

  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
</body>

</html>