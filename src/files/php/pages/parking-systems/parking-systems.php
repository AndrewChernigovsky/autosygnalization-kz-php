<?php

session_start();
$pagePath = $_SERVER['PHP_SELF'];

if (isset($_GET['SELECT'])) {
    $_SESSION['get_params_for_park_systems'] = $_GET;
    error_log(print_r($_SESSION['get_params_for_park_systems'], true)  . ' :SSEESSS ');
}

if (!isset($_GET['SELECT']) && isset($_SESSION['get_params_for_park_systems'])) {
    $savedParams = $_SESSION['get_params_for_park_systems'];
    if (!empty($savedParams)) {
        error_log(print_r($_SERVER['PHP_SELF'], true) . ' :SELF ');
        $redirect_url = $_SERVER['PHP_SELF'] . '?' . http_build_query($savedParams);
        // header("Location: $redirect_url");
        exit();
    }
}

include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../data/article.php';
include_once __DIR__ . '/../../data/select.php';
include_once __DIR__ . '/../../data/products.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../helpers/components/filters/filters.php';
include_once __DIR__ . '/../../helpers/components/setup.php';
include_once __DIR__ . '/../../helpers/components/article.php';
include_once __DIR__ . '/../../helpers/components/product.php';
include_once __DIR__ . '/../../helpers/components/select.php';
include_once __DIR__ . '/../../helpers/components/pagination.php';
include_once __DIR__ . '/../../helpers/components/render-product-cards.php';

$PAGE = $_GET['PAGE'] ?? 1;
$OPTIONS = $_GET ?? [];
$minCost = $_GET['min-value-cost'] ?? 0;
$maxCost = $_GET['max-value-cost'] ?? PHP_FLOAT_MAX;
$SELECT = $_GET['SELECT'] ?? '';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();

$head_path = $docROOT .  '/files/php/layout/head.php';
$title = 'Автосигнализации с автозапуском';
$total_items_per_page = 10;
include_once $head_path;
include_once $docROOT  . '/files/php/data/products.php';
include_once $docROOT  . '/files/php/pages/special-products.php';

$head = new Head($title, [], []);
$filters_render = new FiltersRender($products, "for-park-systems");
$article = new Article();
$articleData = new ArticleData();
$select = new Select();
$selectData = new SelectData();

$filteredProducts = $filters_render->returnCorrectedArr();
$create_product_cards = new CreateProductCards($filteredProducts, false, $total_items_per_page, $PAGE, function () {echo getSpecialOffersSection();});
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include_once $docROOT  . '/files/php/layout/header.php'; ?>
  <main class="main">
    <h2 class="title__h2">Видеорегистраторы</h2>
    <div class="catalog">
      <div class="catalog__wrapper parking-systems">
        <aside class="aside">
          <?= $filters_render->renderFilters(false, true, false); ?>
        </aside>
        <div class="catalog__products">
          <?= $select->createComponent($selectData->getSelectData()) ?>
          <?php if (!empty($filteredProducts)): ?>
            <?= $create_product_cards->renderProductCards(); ?>
          <?php else: ?>
              <p>Нет товаров, соответствующих выбранным фильтрам.</p>
          <?php endif; ?>
        </div>
      </div>
      <?php if ($filteredProducts): ?>
        <?php
          $pagination = new Pagination($filteredProducts, $total_items_per_page);
          ?>
        <?= $pagination->render(); ?>
      <?php endif; ?>
    </div>
    <?= getShop('setup'); ?>
  </main>
  <?php include_once $docROOT  . '/files/php/layout/footer.php'; ?>
  <?php include_once $docROOT  . '/files/php/sections/popups/modal-cart.php'; ?>
</body>

</html>