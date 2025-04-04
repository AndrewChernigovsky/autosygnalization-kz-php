<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
session_start();
$pagePath = $_SERVER['PHP_SELF'];

if (isset($_GET['SELECT'])) {
    $_SESSION['get_params_auto'] = $_GET;
} elseif (!isset($_GET['SELECT']) && isset($_SESSION['get_params_auto'])) {
    $savedParams = $_SESSION['get_params_auto'];
    $redirect_url = $_SERVER['PHP_SELF'] . '?' . http_build_query($savedParams);
    session_unset();
    session_destroy();
    header("Location: $redirect_url");
    exit();
}

use DATA\Products;
use DATA\SelectData;
use DATA\ArticleData;
use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;
use COMPONENTS\Article;
use COMPONENTS\FiltersRender;
use COMPONENTS\CreateProductCards;
use COMPONENTS\Select;
use COMPONENTS\Pagination;
use COMPONENTS\ModalCart;

use function AUTH\SESSIONS\initSession;
use function FUNCTIONS\getShop;

initSession();

$products = (new Products())->getData();

$PAGE = $_GET['PAGE'] ?? 1;
$OPTIONS = $_GET ?? [];
$minCost = $_GET['min-value-cost'] ?? 0;
$maxCost = $_GET['max-value-cost'] ?? PHP_FLOAT_MAX;
$SELECT = $_GET['SELECT'] ?? '';

$title = 'Автосигнализации с автозапуском';
$total_items_per_page = 10;

// include_once $docROOT . $path . '/server/php/pages/special-products.php';

$head = new Head($title, [], []);
$header = new Header();
$footer = new Footer();
$filters_render = new FiltersRender($products, "auto");
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

  <?=$header->getHeader();  ?>
  <main class="main">
    <h2 class="title__h2">Автосигнализации с автозапуском</h2>
    <div class="catalog">
      <div class="catalog__wrapper autosygnals-auto">
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
  <?= (new ModalCart())->render();?>
</body>

</html>