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
$pricesServices = (new PricesServicesData())->getData();

$products = (new Products())->getData();

// ПРОВЕРКА СТРУКТУРЫ БД - получаем список полей из первого продукта
$dbFields = [];
$requiredFields = ['id', 'title', 'price', 'currency', 'price_list', 'prices', 'description', 'category'];
$missingFields = [];
$existingFields = [];

if (!empty($products)) {
  $dbFields = array_keys($products[0]);
  
  foreach ($requiredFields as $field) {
    if (in_array($field, $dbFields)) {
      $existingFields[] = $field;
    } else {
      $missingFields[] = $field;
    }
  }
}

// Фильтруем продукты, у которых есть price_list
$prices = array_filter($products, function ($product) {
  // Проверяем, что price_list не пустой и не null
  if (empty($product['price_list']) || $product['price_list'] === null) {
    return false;
  }

  // Декодируем и проверяем результат
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
          
          <?php if (empty($prices)): ?>
            <div style="background: #3a1a1a; color: #ff5252; padding: 20px; border: 2px solid #ff5252; border-radius: 5px; margin: 20px 0;">
              <h3 style="color: #ff5252; margin-top: 0;">⚠️ Продукты не найдены</h3>
              <p style="color: #ffcdd2;">В базе данных нет продуктов с заполненным полем price_list.</p>
            </div>
          <?php endif; ?>
          
          <ul class="price__list list-style-none">
            <?php foreach ($prices as $price): ?>
              <?php 
                // Декодируем price_list
                $price_list = json_decode($price['price_list'], true);
                $decode_error = json_last_error_msg();
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
                    
                    <?php if ($decode_error !== 'No error' && $decode_error !== 'Нет ошибки'): ?>
                      <p style="color: #ff5252; background: #3a1a1a; padding: 5px 10px; border-radius: 3px; font-size: 12px; margin: 5px 0;">⚠️ Ошибка декодирования JSON: <?= $decode_error ?></p>
                    <?php endif; ?>
                    
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
                    <?php else: ?>
                      <p style="color: #ffb74d; background: #3a2a1a; padding: 5px 10px; border-radius: 3px; font-size: 12px; margin: 5px 0;">⚠️ price_list пустой или не декодируется</p>
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
                        <?php else: ?>
                          <p style="color: #999; background: #2a2a2a; padding: 10px; border-radius: 5px; font-style: italic;">Описание отсутствует</p>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <p style="color: #ffb74d; background: #3a2a1a; padding: 10px; border-radius: 5px;">Нет данных для отображения</p>
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
        
        <?php if (empty($pricesServices)): ?>
          <div style="background: #3a2a1a; color: #ffb74d; padding: 20px; border: 2px solid #ffb74d; border-radius: 5px; margin: 20px 0;">
            <h3 style="color: #极b74d; margin-top: 0;">⚠️ Дополнительные услуги не найдены</h3>
            <p style="color: #ffe0b2;">Список дополнительных услуг пуст. Проверьте метод getData() в классе PricesServicesData.</p>
          </div>
        <?php else: ?>
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
        <?php endif; ?>
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
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalForm())->render(); ?>
  <?= renderPhoneButton(); ?>
</body>

</html>