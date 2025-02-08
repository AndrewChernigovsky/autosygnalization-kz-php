<?php

include_once __DIR__ . '/../../data/products.php';

$data_categories_filters = [
  ["text" => "Автозапуск", "name" => "autosetup", "count" => "1"],
  ["text" => "УПРАВЛЕНИЕ С ТЕЛЕФОНА", "name" => "control-phone", "count" => "1"],
  ["text" => "БЕСПЛАТНЫЙ МОНИТОРИНГ", "name" => "free-monitoring", "count" => "1"],
  ["text" => "УМНАЯ АВТОРИЗАЦИЯ ПО BLUETOOTH SMART", "name" => "bluetooth-smart", "count" => "1"],
  ["text" => "БЛОКИРОВКА ДВИГАТЕЛЯ ПО CAN", "name" => "block-engine-can", "count" => "1"],
  ["text" => "УПРАВЛЕНИЕ ПРЕДПУСКОВЫМ ПОДОГРЕВОМ", "name" => "control-before-start", "count" => "1"],
  ["text" => "УМНАЯ АВТОДИАГНОСТИКА", "name" => "smart-diagnostic", "count" => "1"],
  ["text" => "ДАННЫЕ О ПРОБЕГЕ И УРОВНЕ ТОПЛИВА", "name" => "data-level-bensin", "count" => "1"],
];

/**
 * Обновляет значения count в $data_categories_filters на основе данных из $products.
 *
 * @param array $data_categories_filters
 * @param array $products
 * @return array
 */
function updateFilterCounts($data_categories_filters, $products)
{
    $count = [];

    // Подсчитываем количество товаров для каждого фильтра
    foreach ($products['category'] as $items) {
        foreach ($items as $product) {
            if (isset($product['options-filters']) && is_array($product['options-filters'])) {
                foreach ($product['options-filters'] as $filter) {
                    if (isset($count[$filter])) {
                        $count[$filter]++;
                    } else {
                        $count[$filter] = 1;
                    }
                }
            }
        }
    }

    // Обновляем значения count в $data_categories_filters
    foreach ($data_categories_filters as &$category) {
        $filterKey = $category['name'];
        $category['count'] = $count[$filterKey] ?? 0;
    }

    return $data_categories_filters;
}

// Пример использования
$data_categories_filters = updateFilterCounts($data_categories_filters, $products);

error_log(print_r($data_categories_filters, true) . ' : $data_categories_filters');
?>

<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/navigation-links.php';

$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();
$navigationLinks = new NavigationLinks();
$autosygnals = $navigationLinks->getCategoriesAutoSygnals();
?>

<section class="autosygnals" id="autosygnals">
  <div class="container">
    <h2 class="autosygnals__title">Автосигнализации</h2>
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
              if (isset($data_categories_filters[$index])) {
                  echo $data_categories_filters[$index]['count'];
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