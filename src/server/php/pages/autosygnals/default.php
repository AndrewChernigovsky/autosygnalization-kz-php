<?php

include_once __DIR__ . '/../../data/products.php';

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

    foreach ($products['category'] as $items) {
        foreach ($items as $product) {
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
use HELPERS\SetVariables;
include_once __DIR__ . '/../../data/navigation-links.php';

$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();
$navigationLinks = new NavigationLinks();
$autosygnals = $navigationLinks->getCategoriesAutoSygnals();
?>

<section class="autosygnals" id="autosygnals">
  <div class="container">
    <h2 class="autosygnals__title title__h2">Автосигнализации</h2>
    <div class="autosygnals__wrapper swiper swiper-autosygnals">
      <ul class="autosygnals__list list-style-none swiper-wrapper">
      <?php foreach ($autosygnals as $index => $slide): ?>
  <li class="autosygnals__item swiper-slide">
    <div class="autosygnals__item-card">
      <h3 class="autosygnals__item-title"><?php echo htmlspecialchars($slide['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
      <img class="autosygnals__item-image" src="<?php echo htmlspecialchars($slide['src'], ENT_QUOTES, 'UTF-8') ?>"
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
        <a class="autosygnals__item-link link y-button-primary" href="<?php echo htmlspecialchars($slide['link'], ENT_QUOTES, 'UTF-8'); ?>">В РАЗДЕЛ</a>
      </div>
    </div>
  </li>
<?php endforeach; ?>

      </ul>
      <ul class="autosygnals__pagination swiper-pagination"></ul>
    </div>
  </div>
  </div>
</section>
<?php
include_once __DIR__ . '/../../helpers/components/setup.php';
echo getShop('setup');
?>