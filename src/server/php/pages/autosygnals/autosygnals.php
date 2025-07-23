<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\Products;
use DATA\NavigationLinks;
use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;
use COMPONENTS\ModalCart;
use function FUNCTIONS\renderPhoneButton;
use function FUNCTIONS\getShop;

$title = 'Автосигнализации';
$header = new Header();
$footer = new Footer();
$head = new Head($title, [], []);
$products = (new Products())->getData();
$navigationLinks = new NavigationLinks();

$count_category_austosignals_arr = [
  ["text" => "Автосигнализации с автозапуском", "name" => "auto", "count" => "1"],
  ["text" => "Автосигнализации с GSM", "name" => "gsm", "count" => "1"],
  ["text" => "Автосигнализации без автозапуска", "name" => "without-auto", "count" => "1"],
  ["text" => "Каталог автосигнализаций Starline", "name" => "starline", "count" => "1"],
  ["text" => "Пульты и аксессуары", "name" => "remote-controls", "count" => "1"],
];

function updateFilterCounts($count_category_austosignals_arr, $products)
{
  $count = [];

  foreach ($products as $product) {
    if (isset($product['autosygnals']) && is_array($product['autosygnals'])) {
      foreach ($product['autosygnals'] as $filter) {
        if (isset($count[$filter])) {
          $count[$filter]++;
        } else {
          $count[$filter] = 1;
        }
      }
    }
  }

  foreach ($count_category_austosignals_arr as &$category) {
    $filterKey = $category['name'];
    $category['count'] = $count[$filterKey] ?? 0;
  }

  return $count_category_austosignals_arr;
}

$count_category_austosignals_arr = updateFilterCounts($count_category_austosignals_arr, $products);

?>

<?php
$autosygnals = $navigationLinks->getCategoriesAutoSygnals();
?>


<!DOCTYPE html>
<html lang="ru">
<?= $head->setHead(); ?>

<body>

  <?= $header->getHeader(); ?>
  <main class="main">
    <section class="autosygnals" id="autosygnals">
      <div class="container">
        <h2 class="autosygnals__title title__h2">Автосигнализации</h2>
        <div class="autosygnals__wrapper swiper swiper-autosygnals">
          <ul class="autosygnals__pagination swiper-pagination"></ul>
          <ul class="autosygnals__list list-style-none swiper-wrapper">
            <?php foreach ($autosygnals as $index => $slide): ?>
              <li class="autosygnals__item swiper-slide">
                <a class="autosygnals__item-card"
                  href="<?php echo htmlspecialchars($slide['link'], ENT_QUOTES, 'UTF-8'); ?>">
                  <h3 class="autosygnals__item-title"><?php echo htmlspecialchars($slide['name'], ENT_QUOTES, 'UTF-8'); ?>
                  </h3>
                  <img class="autosygnals__item-image"
                    src="<?php echo htmlspecialchars($slide['src'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Картинка на которой изображена услуга Auto Security Автосигнализации с автозапуском" width="640"
                    height="554">
                  <div class="autosygnals__item-block">
                    <p class="autosygnals__item-count">
                      <span class="autosygnals__item-counter">
                        <?php
                        if (isset($count_category_austosignals_arr[$index])) {
                          echo $count_category_austosignals_arr[$index]['count'];
                        } else {
                          echo 0;
                        }
                        ?>
                      </span> товаров
                    </p>
                    <p class="autosygnals__item-link link y-button-primary">В РАЗДЕЛ</p>
                  </div>
                </a>
              </li>
            <?php endforeach; ?>

          </ul>
        </div>
      </div>
      </div>
    </section>
    <?= getShop('setup'); ?>
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalCart())->render(); ?>
  <?= renderPhoneButton(); ?>
</body>

</html>