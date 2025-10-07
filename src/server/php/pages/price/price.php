<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;

use DATA\Products;
use DATA\PricesServicesData;

use COMPONENTS\ModalForm;

use function FUNCTIONS\renderPhoneButton;
use function FUNCTIONS\getShop;


$title = 'Прайс-лист | Auto Security';
$head = new Head($title, [], []);
$header = new Header();
$footer = new Footer();

$pricesServices = (new PricesServicesData())->getAddedServices();
$products = (new Products())->getData();

// Фильтруем продукты, у которых есть price_list
$prices = array_filter($products, function ($product) {
  if (empty($product['price_list']) || $product['price_list'] === null) {
    return false;
  }
  $decoded = json_decode($product['price_list'], true);
  return !empty($decoded);
});

// Подготавливаем данные для PDF
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
              <?php 
                $price_list = json_decode($price['price_list'], true);
              ?>
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
                    
                    <?php if (!empty($price_list)): ?>
                      <?php foreach ($price_list as $item): ?>
                        <p class="price__item-price">
                          <?php if (!empty($item['title'])): ?>
                            <?= htmlspecialchars($item['title']) ?>
                          <?php endif; ?>
                          <?php if (!empty($item['price'])): ?>
                            <?= htmlspecialchars($item['price']) ?>
                          <?php endif; ?>
                          <?php if (!empty($item['currency'])): ?>
                            <?= htmlspecialchars($item['currency']) ?>
                          <?php endif; ?>
                        </p>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </summary>
                </details>
                <div class="price__content" id="faq-1" role="definition">
                  <div class="price__content-body">
                    <?php if (!empty($price_list)): ?>
                      <?php foreach ($price_list as $item): ?>
                        <?php if (!empty($item['content'])): ?>
                          <div class="service-description">
                            <?= $item['content'] // content — это HTML, не экранируй! ?>
                          </div>
                        <?php endif; ?>
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
                  <?php if (!empty($service['link'])): ?>
                    <a href="<?php echo htmlspecialchars($service['link']); ?>">
                      <?php echo ($service['title']); ?>
                    </a>
                  <?php else: ?>
                    <p><?php echo ($service['title']); ?></p>
                  <?php endif; ?>
                  <div class="price-services__price">
                    <?php echo htmlspecialchars($service['price'])  ?>
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
        <input type="hidden" name="products" value="<?php echo $pricesJson; ?>">
        <input type="hidden" name="addedServices" value="<?php echo $pricesServicesJson; ?>">
        <input type="hidden" name="generate_pdf" value="1">
        <button type="submit" class="button y-button-primary">
          Скачать прайс-лист
        </button>
      </form>
    </div>
    <?= getShop("shop"); ?>
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?= renderPhoneButton(); ?>
</body>

</html>