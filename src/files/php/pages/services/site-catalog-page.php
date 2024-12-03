<?php
$head_path = './../../layout/head.php';
include_once $head_path;
include_once './../../data/paths.php';

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова | Сайт-Каталог';
$canonical = "<link rel='canonical' href='https://xn----7sbbihceda5ae9bf1bg0j.xn--p1ai/files/php/pages/services/site-catalog-page/'/>";
$head = new Head($title, [], [$canonical]);

?>
<!DOCTYPE html>
<html lang="ru">
<?php echo $head->setHead(); ?>

<body>
  <?php
  include $header_path;
  ?>
  <main class="main">
    <div class="container">
      <h1>Сайт-каталог</h1>
      <?php
      include './site-catalog/content.php';
      ?>
      <?php
      include './site-catalog/stages.php';
      ?>
      <?php
      include './site-catalog/purpose.php';
      ?>
      <?php
      include './site-catalog/site.php';
      ?>
      <?php
      include './site-catalog/deadlines.php';
      ?>
      <a href="<?php echo $buy_btn ?>" class="value-button">Заказать</a>
    </div>
  </main>
  <?php
  include $footer_path;
  ?>
</body>

</html>