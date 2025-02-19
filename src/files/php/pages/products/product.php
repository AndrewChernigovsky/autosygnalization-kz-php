

<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/products.php';
include_once __DIR__ . '/../../helpers/components/product.php';

$category = isset($_GET['category']) ? $_GET['category'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

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
$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$sections_path = $docROOT . $path . '/files/php/helpers/include-sections.php';
include_once $head_path;
include_once $sections_path;
$base_path = $docROOT . $path . '/files/php/layout';
$product_section = __DIR__ . '/../../sections/card-tabs.php';

$title = "$id | Auto Security";
$head = new Head($title, [], []);
error_log(print_r($category,true) . 'Я YT ОТРАБОТАЛ');
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include $base_path . '/header.php'; ?>
  <main class="main">
    <section class="card-more">
      <?= $contentImage; ?>
      <?= $contentDescription; ?>
      <div class="product-card__wrapper">
        <div class="product-card__container">
          <p class="product-card__text">Доставка:</p>
          <a class="product-card__link" href="#" style="background-image: url(<?= $path . '/assets/images/vectors/link-icon.svg'; ?>);">о доставке и оплате</a>
        </div>      
        <a class="product-card__link product-card__link--mod" href="#">Наличие товара уточняйте у продавца.</a>
      </div>
      <p class="card-more__text">
        <span>Итоговая стоимость</span>
        <span class="cost-total" id="cost-total"></span>
      </p>
      <?php if (isset($products) && !empty($products)): ?>
      <?php
        $product = null;
          foreach ($products['category'] as $category) {
              foreach ($category as $item) {
                  if ($item['id'] === $id) {
                      $product = $item;
                      break 2;
                  }
              }
          }
          if ($product !== null): ?>
          <div class="card-more__wrapper">
            <p>Количество</p>
            <div class="card-more__button-cost" data-id="<?php echo htmlspecialchars($product['id']); ?>" data-cost="<?= $product['price'] ?>"></div>
          </div>
          <button type="button" class="button y-button-primary card-more__button-cart"
            data-id="<?php echo htmlspecialchars($product['id']); ?>" data-cost="<?= $product['price'] ?>">В корзину</button>
        <?php endif; ?>
    <?php endif; ?>
      <p class="card-more__text card-more__text--info">Цена за материал указана без установки.</p>
    </section>    
    <?php include $product_section;?>
    <div class="modal" id="deliveryModal">
      <div class="modal__content">
        <span class="modal__close-button" id="modalClose">&times;</span>
        <h2 class="modal__title">Оплата и доставка</h2>
        <p class="modal__subtitle">ОБ ОПЛАТЕ</p>
        <p>Оплата осуществляется наличными при покупке и/или установке оборудования, либо при получении товара при после доставки.</p>
        <p>Также, Вы сможете оплатить товар/услугу безналичным расчетом, перечислив деньги на Kaspi Gold: +7 707 747 8212.</p>
        <p class="modal__subtitle">О ДОСТАВКЕ</p>
        <p>Доставка товара по городу осуществляется в течение 1-2 рабочих дня с момента оформления и оплаты заказа.</p>
        <p>Стоимость доставки составляет в черте города от 1.000 тг и выше, в зависимости от удаленности местоположения заказчика, а при заказе на сумму более 40.000 тг - бесплатно!</p>
        <p>Также, Вы можете забрать товар самовывозом из магазина по адресу: Алматы, ул.Щепеткова, 122.</p>
        <p>Обратите внимание, что полноценную гарантию на материал Вы получаете только при установке выбранного Вами товара нашим квалифицированным специалистом!</p>
        <p class="modal__subtitle">ВОЗВРАТ ТОВАРА</p>
        <p>Возврат товара возможен в течение 14 дней с момента продажи и только при сохранении товарного вида изделия, упаковки и комплектующих.</p>
        <p>При установке оборудования на автомобиль возврат товара НЕВОЗМОЖЕН по причине потери товарного вида изделия и его комплектующих и нарушения целостности упаковки товара.</p>
        <p>Соответствие оборудования на товарный вид при возврате решает продавец магазина, либо установщик оборудования.</p>
        <p class="modal__subtitle">СОГЛАШЕНИЕ</p>
        <p>Покупкой/заказом товара/услуги Вы подтверждаете Ваше согласие на условия оплаты, доставки, возврата и обслуживания по гарантии.</p>
      </div>
    </div>
  </main>
  <?php include $base_path . '/footer.php'; ?>
  <?php include_once $docROOT . $path . '/files/php/sections/popups/modal-cart.php'; ?>
</body>

</html>
