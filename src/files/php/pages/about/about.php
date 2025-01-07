<?php
include_once __DIR__ . '/../../api/sessions/session.php';
include_once __DIR__ . '/../../helpers/classes/setVariables.php';
include_once __DIR__ . '/../../data/aboutus.php';

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/files/php/layout/head.php';
$title = 'О нас | Auto Security';

include_once $head_path;

$head = new Head($title, [], []);

$aboutUs = new AboutusData();
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
      <h2>О нас</h2>
      <p>“Auto Security” – установочный центр автоэлектроники. Мы предлагаем лучшее!</p>
      <p>Наша компания была основана в 2004 году, в самый расцвет автосервисов. Миссия нашей компании – предлагать
        качественные услуги в сфере установки и ремонта автоэлектроники.</p>
      <h3>Наши преимущества:</h3>
      <ul>
        <li>Наши мастера имеют богатый опыт по инсталляции разнообразного электронного оборудования на различные
          автомобили.</li>
        <li>Мы постоянно повышаем свою квалификацию, участвуем в конференциях.</li>
        <li>Аккуратность и ответственность – именно это сегодня является важными отличиями команды “Auto Security”.</li>
        <li>Наш сервис укомплектован современным диагностическим оборудованием, позволяющим нам корректно работать с
          абсолютно новыми автомобилями.</li>
        <li>Нашим клиентам мы предлагаем услугу выезда для экономии времени и наибольшего комфорта.</li>
      </ul>
      <p>Дружная команда опытных установщиков с удовольствием воплотит ваши мечты в реальность!</p>
      <p>Обращайтесь к нам,будем рады Вам помочь!</p>
      <h3>ФОТОГРАФИИ ТЕХ ЦЕНТРА</h3>
      <?php
      foreach ($aboutUs->getImagesAboutUs() as $image) {
        echo "<img src='{$image['src']}' alt='картинка' width='600' height='300'>";
      }
      ?>
      <p>Обращайтесь в "Auto Security" - Вам будет оказана квалифицированная помощь по установке дополнительного
        электронного оборудования на Ваш автомобиль! Мы продиагностируем Ваш авто, отремонтируем, установим, настроим
        Ваше оборудование! Доверяйте профессионалам!</p>
      <h3>Отзывы наших клиентов</h3>

      <div class="reviews">
        <ul>
          <?php
          foreach ($aboutUs->getReviewsAboutUs() as $review) {
            echo "
            <li>
                <p>
                  <span>{$review['date']}</span>
                  <span>{$review['name']}</span>
                </p>
                <p>{$review['model']}</p>
                <p>{$review['text']}</p>
            </li>
            ";
          }
          ?>
        </ul>
      </div>
    </div>
  </main>
  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
</body>

</html>