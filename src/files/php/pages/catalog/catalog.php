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
$OPTIONS = $_GET ?? '';
error_log(print_r($OPTIONS, true) . ' : OPTIONS');
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

$filteredProducts = $OPTIONS != '' ? [] : $products;

if (!empty($OPTIONS)) {
  foreach ($products as $product) {
      $isMatch = true;
      foreach ($OPTIONS as $option => $value) {
          // Проверяем, существует ли ключ 'options' и является ли он массивом
          if (!isset($product['options']) || !is_array($product['options'])) {
              error_log("Product has invalid 'options' value.");
              $isMatch = false;
              break;
          }
          if ($value === 'on' && !in_array($option, $product['options'])) {
              $isMatch = false;
              break;
          }
      }
      if ($isMatch) {
          $filteredProducts[] = $product;
          if (isset($product['id'])) {
              error_log("Product ID " . $product['id'] . " added to filtered products.");
          } else {
              error_log("Product added to filtered products.");
          }
      }
  }
}
$pagination = new Pagination($filteredProducts);
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
          <?= getProductCardWModel($filteredProducts, false, $PAGE) ?>
        </div>
      </div>
      <?php if ($filteredProducts): ?>
        <?= $pagination->render(); ?>
      <?php endif; ?>
    </div>
    <?= getShop('setup'); ?>
  </main>
  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
  <?php include_once $docROOT . $path . '/files/php/sections/popups/modal-cart.php'; ?>
</body>

</html>