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
    <!-- ОТЛАДОЧНАЯ ИНФОРМАЦИЯ - УДАЛИТЬ ПОСЛЕ ПРОВЕРКИ -->
    <div style="background: #1a1a1a; color: #fff; padding: 20px; margin: 20px; border: 2px solid #ffd700; border-radius: 8px; font-family: monospace;">
      <h3 style="color: #ffd700; margin-top: 0;">🔍 ОТЛАДКА (удалить после проверки):</h3>
      
      <!-- Проверка структуры БД -->
      <div style="background: #2a2a2a; padding: 15px; margin: 15px 0; border: 1px solid #555; border-radius: 5px;">
        <h4 style="margin-top: 0; color: #4fc3f7;">📊 СТРУКТУРА БАЗЫ ДАННЫХ:</h4>
        
        <?php if (!empty($existingFields)): ?>
          <div style="margin-bottom: 15px;">
            <strong style="color: #4caf50;">✅ Поля присутствуют в БД (<?= count($existingFields) ?>):</strong>
            <ul style="margin: 5px 0; padding-left: 20px; color: #81c784;">
              <?php foreach ($existingFields as $field): ?>
                <li><?= htmlspecialchars($field) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        
        <?php if (!empty($missingFields)): ?>
          <div style="margin-bottom: 15px;">
            <strong style="color: #f44336;">❌ Поля ОТСУТСТВУЮТ в БД (<?= count($missingFields) ?>):</strong>
            <ul style="margin: 5px 0; padding-left: 20px; color: #ef5350;">
              <?php foreach ($missingFields as $field): ?>
                <li><?= htmlspecialchars($field) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        
        <details style="margin-top: 10px;">
          <summary style="cursor: pointer; font-weight: bold; color: #ffeb3b;">📋 Все поля в БД (<?= count($dbFields) ?>)</summary>
          <div style="background: #1a1a1a; padding: 10px; margin-top: 10px; max-height: 200px; overflow: auto; border: 1px solid #444;">
            <ul style="margin: 0; padding-left: 20px; column-count: 3; column-gap: 20px; color: #bbb;">
              <?php foreach ($dbFields as $field): ?>
                <li style="font-size: 12px;"><?= htmlspecialchars($field) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </details>
      </div>
      
      <!-- Статистика данных -->
      <div style="background: #0d47a1; padding: 15px; margin: 15px 0; border: 1px solid #42a5f5; border-radius: 5px; color: #fff;">
        <h4 style="margin-top: 0; color: #64b5f6;">📈 СТАТИСТИКА ДАННЫХ:</h4>
        <p><strong>Всего продуктов получено:</strong> <?= count($products) ?></p>
        <p><strong>Продуктов с price_list:</strong> <?= count($prices) ?></p>
        <p><strong>Дополнительных услуг:</strong> <?= count($pricesServices) ?></p>
      </div>
      
      <?php if (empty($prices)): ?>
        <p style="color: #ff5252; font-weight: bold; background: #3a1a1a; padding: 10px; border-radius: 5px;">❌ НЕТ ПРОДУКТОВ С PRICE_LIST!</p>
      <?php else: ?>
        <p style="color: #69f0ae; font-weight: bold; background: #1a3a1a; padding: 10px; border-radius: 5px;">✅ Продукты с price_list найдены</p>
        <details style="margin-top: 10px;">
          <summary style="cursor: pointer; font-weight: bold; color: #ffd700;">📦 Показать первый продукт</summary>
          <pre style="background: #0a0a0a; color: #0f0; padding: 10px; overflow: auto; max-height: 300px; border: 1px solid #333; border-radius: 4px;"><?php 
            $firstPrice = reset($prices);
            echo "ID: " . $firstPrice['id'] . "\n";
            echo "Title: " . $firstPrice['title'] . "\n";
            echo "Price: " . $firstPrice['price'] . " " . $firstPrice['currency'] . "\n";
            echo "price_list (raw): " . $firstPrice['price_list'] . "\n";
            echo "price_list (decoded): ";
            print_r(json_decode($firstPrice['price_list'], true));
          ?></pre>
        </details>
      <?php endif; ?>
      
      <?php if (empty($pricesServices)): ?>
        <p style="color: #ff5252; font-weight: bold; background: #3a1a1a; padding: 10px; border-radius: 5px;">❌ НЕТ ДОПОЛНИТЕЛЬНЫХ УСЛУГ!</p>
      <?php else: ?>
        <p style="color: #69f0ae; font-weight: bold; background: #1a3a1a; padding: 10px; border-radius: 5px;">✅ Дополнительные услуги найдены</p>
        <details style="margin-top: 10px;">
          <summary style="cursor: pointer; font-weight: bold; color: #ffd700;">🛠️ Показать первую услугу</summary>
          <pre style="background: #0a0a0a; color: #0f0; padding: 10px; overflow: auto; border: 1px solid #333; border-radius: 4px;"><?php print_r($pricesServices[0]); ?></pre>
        </details>
      <?php endif; ?>
    </div>
    <!-- КОНЕЦ ОТЛАДОЧНОЙ ИНФОРМАЦИИ -->
    
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