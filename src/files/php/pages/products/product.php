<?php
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/products.php';
include_once __DIR__ . '/../../helpers/components/product.php';


$category = isset($_GET['category']) ? $_GET['category'] : null;
$model = isset($_GET['model']) ? $_GET['model'] : null;

function getAutoContent($products, $category, $model)
{
  $result = "";
  switch ($category) {
    case 'keychain':
      $result .= getProductCard($products, $model);
      return $result;
    default:
      return 'Контент не найден.';
  }
}

$content = getAutoContent($products, $category, $model);
$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$sections_path = $docROOT . $path . '/files/php/helpers/include-sections.php';
include_once $head_path;
include_once $sections_path;
$base_path = $docROOT . $path . '/files/php/layout';

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова';
$head = new Head($title, [], []);
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
  <?php include $base_path . '/header.php'; ?>
  <main class="main">
    <?= $content; ?>
    <?= include_once __DIR__ . '/../../sections/card-tabs.php'; ?>
  </main>
  <?php include $base_path . '/footer.php'; ?>
</body>

</html>