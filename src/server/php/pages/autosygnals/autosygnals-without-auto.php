<?php


session_start();
$pagePath = $_SERVER['PHP_SELF'];

if (isset($_GET['SELECT'])) {
    $_SESSION['get_params_without_auto'] = $_GET;
}

elseif (!isset($_GET['SELECT']) && isset($_SESSION['get_params_without_auto'])) {
    $savedParams = $_SESSION['get_params_without_auto'];
    $redirect_url = $_SERVER['PHP_SELF'] . '?' . http_build_query($savedParams);
    session_unset();
    session_destroy();
    header("Location: $redirect_url");
    exit();
}
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../data/article.php';
include_once __DIR__ . '/../../data/select.php';
include_once __DIR__ . '/../../data/products.php';
use HELPERS\SetVariables;
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
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/server/php/layout/head.php';
$title = 'Автосигнализации без автозапуском';
$total_items_per_page = 10;
include_once $head_path;
include_once $docROOT . $path . '/server/php/data/products.php';
include_once $docROOT . $path . '/server/php/pages/special-products.php';

$head = new Head($title, [], []);
$filters_render = new FiltersRender($products,"without-auto");
$article = new Article();
$articleData = new ArticleData();
$select = new Select();
$selectData = new SelectData();

$filteredProducts = $filters_render->returnCorrectedArr();
$create_product_cards = new CreateProductCards($filteredProducts, false, $total_items_per_page, $PAGE, function() {echo getSpecialOffersSection();});
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include_once $docROOT . $path . '/server/php/layout/header.php'; ?>
  <main class="main">
    <h2 class="title__h2">Автосигнализации без автозапуска</h2>
    <div class="catalog">
      <div class="catalog__wrapper autosygnals-without-auto">
        <aside class="aside">
          <?= $filters_render->renderFilters(); ?>
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
  <?php include_once $docROOT . $path . '/server/php/layout/footer.php'; ?>
  <?php include_once $docROOT . $path . '/server/php/sections/popups/modal-cart.php'; ?>
</body>

</html>