<?php
$head_path = './../../layout/head.php';
include_once $head_path;
include_once './../../data/paths.php';
include_once './../../data/contacts.php';
$title = 'Создание и продвижение сайтов | Академия Андрея Андреевича Изосимова | Портфолио';
$head = new Head($title, [], []);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
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
      <h1>Сайт о еде</h1>
      <img src="<?php echo $portfolio_site_4 ?>"
        alt="сайт, разработка сайтов, Андрей Андреевич, сайт, пример работ сайтов">
      <a href="<?php echo $buy_btn ?>" class="value-button">Заказать</a>
    </div>
  </main>
  <?php
  include $footer_path;
  ?>
</body>

</html>