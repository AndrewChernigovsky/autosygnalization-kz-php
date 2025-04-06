<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\Products;
use DATA\SelectData;
use DATA\ArticleData;
use DATA\NavigationLinks;
use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;
use COMPONENTS\Article;
use COMPONENTS\FiltersRender;
use COMPONENTS\CreateProductCards;
use COMPONENTS\Select;
use COMPONENTS\Pagination;
use COMPONENTS\ModalCart;
use COMPONENTS\SpecialProducts;

use function AUTH\SESSIONS\initSession;
use function FUNCTIONS\getShop;
use function FUNCTIONS\getParamsAutosygnals;

initSession();
getParamsAutosygnals("get_params_catalog");


$PAGE = $_GET['PAGE'] ?? 1;
$OPTIONS = $_GET ?? [];
$minCost = $_GET['min-value-cost'] ?? 0;
$maxCost = $_GET['max-value-cost'] ?? PHP_FLOAT_MAX;
$SELECT = $_GET['SELECT'] ?? '';

$total_items_per_page = 10;
$title = 'Автосигнализации с автозапуском';

$head = new Head($title, [], []);
$header = new Header();
$footer = new Footer();
$products = (new Products())->getData();

$filters_render = new FiltersRender($products);
$article = new Article();
$articleData = new ArticleData();
$select = new Select();
$selectData = new SelectData();

$filteredProducts = $filters_render->returnCorrectedArr();
$create_product_cards = new CreateProductCards($filteredProducts, false, $total_items_per_page, $PAGE, function () {
    $special = (new SpecialProducts())->render();
    echo $special;
    ;
});
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
<?= $header->getHeader(); ?>
  <main class="main">
    <h2 class="title__h2">Каталог всех товаров</h2>
    <div class="catalog">
      <div class="catalog__wrapper autosygnals-gsm">
        <aside class="aside">
          <?= $filters_render->renderFilters(); ?>
        </aside>
        <div class="catalog__products">
          <?= $select->createComponent($selectData->getData()) ?>
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
  <?= $footer->getFooter(); ?>
  <?= (new ModalCart())->render(); ?>
</body>

</html>