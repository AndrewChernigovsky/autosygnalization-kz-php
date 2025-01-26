<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../data/article.php';
include_once __DIR__ . '/../../data/select.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../helpers/components/filters/filters.php';
include_once __DIR__ . '/../../helpers/components/filters/sorting.php';
include_once __DIR__ . '/../../helpers/components/setup.php';
include_once __DIR__ . '/../../helpers/components/product.php';
include_once __DIR__ . '/../../helpers/components/article.php';
include_once __DIR__ . '/../../helpers/components/select.php';
include_once __DIR__ . '/../../helpers/components/pagination.php';

$PAGE = $_GET['PAGE'] ?? 1;
$OPTIONS = $_GET ?? [];
$SELECT = $_GET['SELECT'] ?? '';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$title = 'Каталог | Auto Security';

include_once $head_path;
include_once $docROOT . $path . '/files/php/data/filters.php';
include_once $docROOT . $path . '/files/php/data/products.php';

$head = new Head($title, [], []);
$filters = new Filters($data_categories_filters);
$sorting = new Sorting();
$article = new Article();
$articleData = new ArticleData();
$select = new Select();
$selectData = new SelectData();

$allProducts = $products;

// Фильтрация по OPTIONS
if (empty($OPTIONS)) {
    $filteredProducts = $allProducts;
} else {
    $filteredProducts = [];
    foreach ($allProducts['category'] as $category => $items) {
        foreach ($items as $product) {
            $isMatch = true;
            foreach ($OPTIONS as $option => $value) {
                $productFilters = $product['options-filters'] ?? [];
                if ($value === 'on') {
                    if (!is_array($productFilters) || !in_array($option, $productFilters)) {
                        $isMatch = false;
                        break;
                    }
                }
            }
            if ($isMatch) {
                $filteredProducts[] = $product;
            }
        }
    }
}

if (!empty($SELECT) && $SELECT === 'name') {
    error_log(print_r($filteredProducts, true) . ' : FILTERS');
    usort($filteredProducts, function ($a, $b) {
        $nameA = $a['title'] ?? '';
        $nameB = $b['title'] ?? '';
        return strcmp(mb_strtolower($nameA), mb_strtolower($nameB));
    });
}
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include_once $docROOT . $path . '/files/php/layout/header.php'; ?>
  <main class="main">
    <h2 class="title__h2">АВТОСИГНАЛИЗАЦИИ С АВТОЗАПУСКОМ</h2>
    <div class="catalog">
      <div class="catalog__wrapper">
        <aside class="aside">
          <?= $filters->renderFilters(); ?>
        </aside>
        <div class="catalog__products">
          <?= $select->createComponent($selectData->getSelectData()) ?>
          <?php if (!empty($filteredProducts)): ?>
              <?= getProductCardWModel($filteredProducts, false, $PAGE) ?>
          <?php else: ?>
              <p>Нет товаров, соответствующих выбранным фильтрам.</p>
          <?php endif; ?>
        </div>
      </div>
      <?php if ($filteredProducts): ?>
        <?php
          $pagination = new Pagination($filteredProducts);
          ?>
        <?= $pagination->render(); ?>
      <?php endif; ?>
    </div>
    <?= getShop('setup'); ?>
  </main>
  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
  <?php include_once $docROOT . $path . '/files/php/sections/popups/modal-cart.php'; ?>
</body>

</html>