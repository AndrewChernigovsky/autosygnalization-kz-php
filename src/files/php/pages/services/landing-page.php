<?php
$head_path = './../../layout/head.php';
include_once $head_path;
include_once './../../data/paths.php';

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова | Лендинг';
$canonical = "<link rel='canonical' href='https://xn----7sbbihceda5ae9bf1bg0j.xn--p1ai/files/php/pages/services/landing-page/'/>";
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
      <h1>Лендинг</h1>
      <?php
      include './landing/content.php';
      ?>
      <?php
      include './landing/stages.php';
      ?>
      <?php
      include './landing/purpose.php';
      ?>
      <?php
      include './landing/site.php';
      ?>
      <?php
      include './landing/deadlines.php';
      ?>
      <a href="<?php echo $buy_btn ?>" class="value-button">Заказать</a>
    </div>
  </main>
  <?php
  include $footer_path;
  ?>
</body>

</html>