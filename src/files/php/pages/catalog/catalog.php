<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../helpers/components/filters/filters.php';
include_once __DIR__ . '/../../helpers/components/filters/sorting.php';
include_once __DIR__ . '/../../helpers/components/setup.php';
include_once __DIR__ . '/../../helpers/components/product.php';
include_once __DIR__ . '/../../helpers/components/article.php';
include_once __DIR__ . '/../../data/article.php';
include_once __DIR__ . '/../../data/select.php';
include_once __DIR__ . '/../../helpers/components/select.php';

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

?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include_once $docROOT . $path . '/files/php/layout/header.php'; ?>
  <main class="main">
    <script>
      var products = <?php echo json_encode($products); ?>;
      console.log(products, 'Products');
      console.log(products.category.keychain, 'category');

    </script>
    <div class="container">
      <h2>АВТОСИГНАЛИЗАЦИИ С АВТОЗАПУСКОМ</h2>
      <div class="catalog">
        <aside class="aside">
          <?= $filters->renderFilters() ?>
          <div class="aside__offers">
            <?php foreach ($articleData->getArticleData() as $data): ?>
              <?= $article->createComponent($data); ?>
            <?php endforeach; ?>
          </div>
        </aside>
        <div class="catalog__products-wrapper">
          <div class="catalog__products-sort">
            <button type="button" class="catalog__products-sort-button" id="button-filter">Фильтр</button>
            <?= $sorting->renderFilters(); ?>
          </div>
          <div class="catalog__products">
            <?= getProductCardWModel($products) ?>
          </div>
        </div>
      </div>
    </div>
    <?= $select->createComponent($selectData->getSelectData()) ?>
    <?= getShop('setup'); ?>
  </main>
  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
  <?php include_once $docROOT . $path . '/files/php/sections/popups/modal-cart.php'; ?>
</body>

</html>