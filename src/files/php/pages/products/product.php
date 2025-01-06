<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/products.php';
include_once __DIR__ . '/../../helpers/components/product.php';


$category = isset($_GET['category']) ? $_GET['category'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

function getAutoContent($products, $category, $id)
{
  $result = "";
  switch ($category) {
    case 'keychain':
      $result .= getProductCard($products, $id);
      return $result;
    default:
      return 'Контент не найден.';
  }
}

$content = getAutoContent($products, $category, $id);
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
    <div class="container">
      <div class="product-card__wrapper">
        <div class="product-card__container">
          <p class="product-card__text">Доставка:</p>
          <a class="product-card__link" href="#" style="background-image: url(<?= $path . '/assets/images/vectors/link-icon.svg'; ?>);">о доставке и оплате</a>
        </div>      
        <a class="product-card__link" href="#">Наличие товара уточняйте у продавца.</a>
      </div>
      <?= $content; ?>
    </div>    
    <?= include_once __DIR__ . '/../../sections/card-tabs.php'; ?>
  </main>
  <?php include $base_path . '/footer.php'; ?>
</body>

</html>