<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use LAYOUT\Header;
use LAYOUT\Footer;
use LAYOUT\Head;
use COMPONENTS\AutoCategory;

use function AUTH\SESSIONS\initSession;
use function FUNCTIONS\getShop;

initSession();

$title = 'Автосигнализации | Auto Security';
$head = new Head($title, [], []);
$header = new Header();
$footer = new Footer();

$categories = [
   "Автосигнализации с автозапуском",
   "Автосигнализации с автозапуском1",
   "Автосигнализации с автозапуском2",
]
?>

<!DOCTYPE html>
<html lang="ru">
<?= $head->setHead();?>
<body>
<?php
  foreach ($categories as $category) {
      $autoCategory = new AutoCategory();
      echo $autoCategory->render($category);
  }
?>
</body>
<?=        getShop('setup');?>
<?= $footer->getFooter();?>

</html>