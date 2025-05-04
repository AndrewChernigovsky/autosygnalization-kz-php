<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use DATA\Products;
use DATA\SelectData;
use DATA\ArticleData;
use LAYOUT\Header;
use LAYOUT\Head;
use LAYOUT\Footer;
use COMPONENTS\Article;
use COMPONENTS\FiltersRender;
use COMPONENTS\Select;
use COMPONENTS\Pagination;
use COMPONENTS\ModalCart;
use COMPONENTS\CreateProductCards;

use function SECTIONS\sertificatesSection;
use function FUNCTIONS\getShop;
use function AUTH\SESSIONS\initSession;

initSession();

$PAGE = $_GET['PAGE'] ?? 1;
$OPTIONS = $_GET ?? [];
$minCost = $_GET['min-value-cost'] ?? 0;
$maxCost = $_GET['max-value-cost'] ?? PHP_FLOAT_MAX;
$SELECT = $_GET['SELECT'] ?? '';
$TYPE = $_GET['type'] ?? "auto";

$total_items_per_page = 10;
$titleDocument = "Каталог | Auto Security";
$title = "Специальные предложения";

$header = new Header();
$footer = new Footer();
$products = (new Products())->getData();

$head = new Head($title, [], []);

$filters_render = new FiltersRender($products, $TYPE);
$filteredProducts = $filters_render->returnCorrectedArr();
$create_product_cards = new CreateProductCards($filteredProducts, false, $total_items_per_page, $PAGE);

$article = new Article();
$articleData = new ArticleData();
$select = new Select();
$selectData = new SelectData();
?>


<!DOCTYPE html>
<html lang="ru">
<?= $head->setHead(); ?>

<body>

  <?= $header->getHeader(); ?>
  <main class="main">
    <h2 class="title__h2"><?= $title ?></h2>
  <?= sertificatesSection();?>
   
    <?= getShop('setup'); ?>
  </main>
  <?= $footer->getFooter(); ?>
  <?= (new ModalCart())->render(); ?>
</body>

</html>