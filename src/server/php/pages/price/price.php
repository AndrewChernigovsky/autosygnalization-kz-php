<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;

use DATA\PricesData;
use DATA\PricesServicesData;
use COMPONENTS\ModalForm;
use function FUNCTIONS\renderPhoneButton;
use DATA\Products;

$title = 'Прайс-лист | Auto Security';
$head = new Head($title, [], []);
$header = new Header();
$footer = new Footer();

// $prices = (new PricesData())->getData();
$pricesServices = (new PricesServicesData())->getAddedServices();

$products = (new Products())->getData();
$prices = array_filter($products, function ($product) {
  // Проверяем, что price_list не пустой и не null
  if (empty($product['price_list']) || $product['price_list'] === null) {
    return false;
  }

  // Декодируем и проверяем результат
  $decoded = json_decode($product['price_list'], true);
  return !empty($decoded);
});

error_log(print_r($prices, true) . 'prices22');

$pricesJson = htmlspecialchars(json_encode($prices), ENT_QUOTES, 'UTF-8');
$pricesServicesJson = htmlspecialchars(json_encode($pricesServices), ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?= $header->getHeader(); ?>
  <main class="main">
    <section class="price">
      <div class="container">
        <div class="price__wrapper">
          <h1 class="price__title">Прайс</h1>
          <h2 class="price__subtitle">Прайс по оборудованию Starline и цены на установку:<span>*</span></h2>
          <ul class="price__list list-style-none">
            <?php foreach ($prices as $price): ?>
              <li class="price__item">
                <details class="price__details product-cart">
                  <summary class="price__summary">
                    <p class="price__item-title" role="term" aria-details="faq-1">
                      <?= htmlspecialchars($price['title']); ?>
                    </p>
                    <?php $price_list = json_decode($price['price_list'], true); ?>
                    <div class="price__item-box">
                      <span class="price__item-product"><?= htmlspecialchars($price['price']); ?></span>
                      <span class="price__item-currency"><?= htmlspecialchars($price['currency']); ?></span>
                    </div>
                    <?php if (!empty($price_list)): ?>

                      <p class="price__item-price">
                        <?= htmlspecialchars($price_list[0]['title']) ?>
                        <?= htmlspecialchars($price_list[0]['price']) ?>
                        <?= $price_list[0]['content'] ?>
                      </p>
                    <?php endif; ?>
                  </summary>
                </details>
                <div class="price__content" id="faq-1" role="definition">
                  <div class="price__content-body">
                    <?php if (!empty($price['prices'])): ?>
                      <?php foreach ($price['prices'] as $service): ?>
                        <div class="service-description">
                          <?= $service['content'] ?>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </section>
    <section class="price-services">
      <div class="container">
        <h2 class="price-services__title">Прайс на дополнительные услуги:<span>*</span></h2>
        <ul class="price-services__list list-style-none">
          <?php foreach ($pricesServices as $service): ?>
            <li>
              <div class="price-services__box">
                <p><?php echo ($service['title']); ?></p>
                <div class="price-services__price">
                  <?php echo htmlspecialchars($service['price']) ?>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
        <p class="price-services__warning">* Цена услуг зависит от автомобиля и сложности работ.<br><br> Обязательно
          нужно уточнять у мастера совместимость оборудования и необходимый набор функций.<br><br> Все нюансы
          оговариваются при осмотре автомашины.</p>
      </div>
    </section>
    <div class="price-button">
      <form method="POST" action="/server/php/admin/api/docs/price-list.php">
        <input type="hidden" name="products" value="<?php echo $pricesJson?>">
        <input type="hidden" name="addedServices" value="<?php echo $pricesServicesJson?>">
        <input type="hidden" name="generate_pdf" value="1">
        <button type="submit" class="button y-button-primary">
          Скачать прайс-лист
        </button>
      </form>
    </div>
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?= renderPhoneButton(); ?>
</body>

</html>