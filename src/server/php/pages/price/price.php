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
use DATA\ServicesData;

$title = 'Прайс-лист | Auto Security';
$head = new Head($title, [], []);
$header = new Header();
$footer = new Footer();

// $prices = (new PricesData())->getData();
// $pricesServices = (new PricesServicesData())->getData();

$products = (new Products())->getData();
$services = (new ServicesData())->getData();
$prices = $products;
error_log(print_r($services, true) . 'services22');

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
              <?php if (!empty($price['prices'])): ?>
              <li class="price__item">
                <details class="price__details product-cart">
                  <summary class="price__summary">
                    <p class="price__item-title" role="term" aria-details="faq-1">
                      <?= htmlspecialchars($price['title']); ?>
                    </p>
                    <div class="price__item-box">
                      <span class="price__item-product"><?= htmlspecialchars($price['price']); ?></span>
                      <span class="price__item-currency"><?= htmlspecialchars($price['currency']); ?></span>
                    </div>
                    <?php if (!empty($price['prices'])): ?>
                      <?php foreach ($price['prices'] as $service): ?>
                        <p class="price__item-price">
                          установка от <?= htmlspecialchars($service['price']) ?>
                          <?= htmlspecialchars($price['currency']) ?>
                        </p>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </summary>
                </details>
                <div class="price__content" id="faq-1" role="definition">
                  <div class="price__content-body">
                    <?php if (!empty($price['prices'])): ?>
                      <?php foreach ($price['prices'] as $service): ?>
                        <div class="service-description">
                          <?= $service['content'] // content — это HTML, не экранируй! ?>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                  </div>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </section>
    <section class="price-services">
      <div class="container">
        <h2 class="price-services__title">Прайс на дополнительные услуги:<span>*</span></h2>

        <ul class="price-services__list list-style-none">
          <?php if (!empty($services['added'])): ?>
            <?php foreach ($services['added'] as $service1): ?>
              <li>
                <div class="price-services__box">
                  <p><?php echo htmlspecialchars($service1['title']); ?></p>
                  <div class="price-services__price">
                    <?php echo htmlspecialchars($service1['price']); ?>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
        <p class="price-services__warning">* Цена услуг зависит от автомобиля и сложности работ.<br><br> Обязательно
          нужно уточнять у мастера совместимость оборудования и необходимый набор функций.<br><br> Все нюансы
          оговариваются при осмотре автомашины.</p>
      </div>
    </section>
    <div class="price-button">
      <button class="button y-button-primary" id="download-prices">Скачать прайс-лист</button>
      <!-- <a class="button y-button-primary" href="/client/docs/Auto_Security_price.pdf"
        download="Auto-Security-price-2025.pdf" id="download-prices">Скачать прайс-лист</a> -->
    </div>
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?= renderPhoneButton(); ?>
</body>

</html>