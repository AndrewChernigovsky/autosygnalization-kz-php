<?php
$head_path = './../../layout/head.php';
include_once $head_path;
include_once './../../data/paths.php';
include_once './../../data/contacts.php';

$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова | Портфолио';
$canonical = "<link rel='canonical' href='https://xn----7sbbihceda5ae9bf1bg0j.xn--p1ai/files/php/pages/portfolio/site-1'/>";
$head = new Head($title, [], [$canonical]);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset='utf-8'>
  <?php echo $head->setHead(); ?>
  <style>
    .value-button {
      display: flex;
      justify-content: center;
      margin: 0 auto;
      margin-top: 20px;
    }

    p {
      font-family: 'Rubick', 'Arial', sans-serif;
    }

    p a {
      color: white;
      text-decoration: none;
      text-transform: none;
      font-weight: 400;
      font-family: 'Rubick', 'Arial', sans-serif;
    }
  </style>
</head>


<body>
  <?php
  include $header_path;
  ?>
  <main class="main">
    <div class="container">
      <h1>Сайт Аквафильтры</h1>
      <p><a href="<?php echo $portfolio_site_link_1 ?>" rel="nofollow"><?php echo $portfolio_site_link_1 ?></a></p>
      <img src="<?php echo $portfolio_site_1 ?>"
        alt="сайт, разработка сайтов, Андрей Андреевич, аквафильтры сайт, пример работ сайтов">
      <a href="<?php echo $buy_btn ?>" class="value-button">Заказать</a>
    </div>
  </main>
  <?php
  include $footer_path;
  ?>
</body>

</html>