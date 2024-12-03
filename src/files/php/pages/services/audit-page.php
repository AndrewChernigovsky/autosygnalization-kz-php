<?php
$head_path = './../../layout/head.php';
include_once $head_path;
include_once './../../data/paths.php';

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова | Аудит';
$canonical = "<link rel='canonical' href='https://xn----7sbbihceda5ae9bf1bg0j.xn--p1ai/files/php/pages/services/audit-page/'/>";
$head = new Head($title, [], [$canonical]);

?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset='utf-8'>
  <?php echo $head->setHead(); ?>
</head>

<body>
  <?php
  include $header_path;
  ?>
  <main class="main">
    <div class="container">
      <h1>Аудит, стоимость: от 3 000 Р</h1>
      <p>Аудит включен в любую услугу отличную от этой по умолчанию, в случае покупки услуги "Аудит", и покупки услуги
        "Лендинг" или любой другой, из стоимости будет вычтена стоимость услуги "Аудит".
        <?php
        include './audit/content.php';
        ?>
        <?php
        include './audit/stages.php';
        ?>
        <?php
        include './audit/purpose.php';
        ?>
        <a href="<?php echo $buy_btn ?>" class="value-button">Заказать</a>
    </div>
  </main>
  <?php
  include $footer_path;
  ?>
</body>

</html>