<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../helpers/components/filters/filters.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';

$title = 'Каталог | Auto Security';

include_once $head_path;

$head = new Head($title, [], []);
$filters = new Filters();
?>


<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include_once $docROOT . $path . '/files/php/layout/header.php'; ?>
  <main class="main">
    <div class="container">
      <h2>Каталог Товаров</h2>
      <aside class="aside">
        <?php echo $filters->renderFilters() ?>
      </aside>
    </div>
  </main>
  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
</body>

</html>