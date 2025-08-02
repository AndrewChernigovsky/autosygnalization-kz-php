<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use LAYOUT\Header;
use LAYOUT\Footer;
use LAYOUT\Head;
use DATA\Products;
use COMPONENTS\ModalCart;
use COMPONENTS\ModalForm;

use function AUTH\SESSIONS\initSession;
use function FUNCTIONS\getProductCardImage;
use function FUNCTIONS\getProductCardDescription;
use function SECTIONS\cardTabsSection;
use function FUNCTIONS\renderPhoneButton;

initSession();

$category = isset($_GET['category']) ? $_GET['category'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

$products = (new Products())->getData();

function formatPriceWithSpaces($price)
{
  return number_format((int) $price, 0, '', ' ');
}
function getAutoContent($products, $category, $id)
{
  $result = "";
  switch ($category) {
    case 'keychain':
    case 'remote-controls':
    case 'park-systems':
      $result .= getProductCardImage($products, $id);
      return $result;
    default:
      return 'Контент не найден.';
  }
}

$contentImage = getAutoContent($products, $category, $id);
$contentDescription = getProductCardDescription($products, $id);

$title = "$id | Auto Security";
$head = new Head($title, [], []);
$header = new Header();
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?= $header->getHeader(); ?>
  <main class="main">
    <section class="card-more">
      <?= $contentImage; ?>
      <?= $contentDescription; ?>
      <div class="product-card__wrapper">
        <a class="product-card__link product-card__link--mod" href="#">Наличие товара необходимо уточнить у
          менеджера.</a>
      </div>
      <?php if (isset($products) && !empty($products)): ?>
        <?php
        $product = null;
        foreach ($products as $item) {
          if ($item['id'] === $id) {
            $product = $item;
            break;
          }
        }

        if ($product !== null):
          $price = formatPriceWithSpaces($product['price']);
          ?>
          <div class="card-more__wrapper">
            <div class="card-more__button-cost-wrapper">
              <p>Количество</p>
              <div class="card-more__button-cost" data-id="<?php echo htmlspecialchars($product['id']); ?>"
                data-cost="<?= $product['price'] ?>"></div>
            </div>
            <div class="card-more__text">
              <p class="card-more__text card-more__text--info">Цена за материал указана без установки.</p>
              <p class="card-more__text--cost">
                <span>Итоговая стоимость</span>
                <b>
                  <span class="cost-total" id="cost-total"></span>
                </b>
              </p>
            </div>
          </div>
          <button type="button" class="button y-button-primary card-more__button-cart"
            data-id="<?php echo htmlspecialchars($product['id']); ?>" data-cost="<?= $product['price'] ?>">В
            корзину</button>
        <?php endif; ?>
      <?php endif; ?>
    </section>
    <?= cardTabsSection($_GET['id']) ?>

  </main>
  <?php
  echo (new Footer())->getFooter();
  echo (new ModalCart())->render();
  echo (new ModalForm())->render();
  echo renderPhoneButton();


  ?>
</body>

</html>