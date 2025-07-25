<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;
use DATA\PricesData;
use DATA\PricesServicesData;
use COMPONENTS\ModalForm;
use function FUNCTIONS\renderPhoneButton;

$title = 'Прайс-лист | Auto Security';
$head = new Head($title, [], []);
$header = new Header();
$footer = new Footer();

$prices = (new PricesData())->getData();
$pricesServices = (new PricesServicesData())->getData();
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
            <?php
            foreach ($prices as $price): ?>
              <li class="price__item">
                <details class="price__details product-cart">
                  <summary class="price__summary">
                    <p class="price__item-title" role="term" aria-details="faq-1">
                      <?php echo htmlspecialchars($price['title']); ?>
                    </p>
                    <div class="price__item-box">
                      <span class="price__item-product"><?php echo htmlspecialchars($price['productPrice']); ?></span>
                      <span class="price__item-currency"><?php echo htmlspecialchars($price['currency']); ?></span>
                    </div>
                    <p class="price__item-price">установка от
                      <?php echo htmlspecialchars($price['installationPrice']) . ' ' . htmlspecialchars($price['currency']) . '*'; ?>
                    </p>
                  </summary>
                </details>
                <div class="price__content" id="faq-1" role="definition">
                  <div class="price__content-body">
                    <ul class="price__item-description">
                      <?php foreach ($price['description'] as $descItem): ?>
                        <li class="price__item-text"><?php echo htmlspecialchars($descItem); ?></li>
                      <?php endforeach; ?>
                    </ul>
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
                <?php if (!empty($service['link'])): ?>
                  <a href="<?php echo htmlspecialchars($service['link']); ?>">
                    <?php echo ($service['title']); ?>
                  </a>
                <?php else: ?>
                  <p><?php echo ($service['title']); ?></p>
                <?php endif; ?>
                <div class="price-services__price">
                  <?php echo htmlspecialchars($service['productServicesPrice']) . ' ' . htmlspecialchars($service['currency']); ?>
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
      <a class="button y-button-primary" href="/client/docs/Auto_Security_price.pdf"
        download="Auto-Security-price-2025.pdf">Скачать прайс-лист</a>
    </div>
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?= renderPhoneButton(); ?>
</body>

</html>