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
    <section class="about-us">
      <div class="container">
        <div class="about-us__present present">
          <div class="present__block">
            <h2 class="present__main-title">О нас</h2>
            <p class="present__text">“Auto Security” – установочный центр автоэлектроники. Мы предлагаем лучшее!</p>
            <p class="present__text">Наша компания была основана в 2004 году, в самый расцвет автосервисов. Миссия нашей
              компании – предлагать
              качественные услуги в сфере установки и ремонта автоэлектроники.</p>
          </div>
          <div class="present__block">
            <h3 class="present__list-title">Наши преимущества</h3>
            <ul class="present__list list-style-none">
              <li class="present__list-item">Наши мастера имеют богатый опыт по инсталляции разнообразного электронного
                оборудования на различные автомобили.</li>
              <li class="present__list-item">Мы постоянно повышаем свою квалификацию, участвуем в конференциях.</li>
              <li class="present__list-item">Аккуратность и ответственность – именно это сегодня является важными
                отличиями команды “Auto Security”.</li>
              <li class="present__list-item">Наш сервис укомплектован современным диагностическим оборудованием,
                позволяющим нам корректно работать с абсолютно новыми автомобилями.</li>
              <li class="present__list-item">Нашим клиентам мы предлагаем услугу выезда для экономии времени и
                наибольшего комфорта.</li>
            </ul>
            <div class="present__list-comment">
              <p class="present__comment-text">Дружная команда опытных установщиков с удовольствием воплотит ваши мечты
                в реальность!</p>
              <p class="present__comment-text">Обращайтесь к нам,будем рады Вам помочь!</p>
            </div>

          </div>
        </div>
        <div class="about-us__tech-photo tech-photo">
          <h3 class="tech-photo__title">ФОТОГРАФИИ ТЕХ ЦЕНТРА</h3>
          <div class="tech-photo__swiper swiper swiper-tech-photo">
            <div class="tech-photo__swiper-wrapper swiper-wrapper">
              <?php
              foreach ($aboutUs->getImagesAboutUs() as $image) {
                echo "<div class='tech-photo__swiper-slide swiper-slide'>
                <img src='{$image['src']}' alt='картинка' width='600' height='300'>
                </div>";
              }
              ?>
            </div>
            <div class="tech-photo__swiper-pagination swiper-pagination"></div>
          </div>
          <div class="tech-photo__block">
            <h3 class="tech-photo__block-title"><span>Обращайтесь в</span> <span>Auto Security</span></h3>
                      <p class="tech-photo__text">Вам будет оказана квалифицированная помощь по
                        установке дополнительного
                        электронного оборудования на Ваш автомобиль! Мы продиагностируем Ваш авто, отремонтируем, установим,
                        настроим
                        Ваше оборудование! Доверяйте профессионалам!</p>
          </div>

        </div>
        <div class="about-us__reviews reviews">
          <h3 class="reviews__title">Отзывы наших клиентов</h3>
          <div class="reviews__swiper swiper swiper-reviews">
            <ul class="reviews__swiper-wrapper swiper-wrapper list-style-none">
              <?php
              foreach ($aboutUs->getReviewsAboutUs() as $review) {
                echo "
              <li class='reviews__swiper-slide swiper-slide'>
              <div class='swiper-slide__header'>
                <p class='swiper-slide__header-block'>
                  <span class='swiper-slide__header-date'>{$review['date']}</span>
                  <span class='swiper-slide__header-model'>{$review['model']}</span>
                </p>
                <p class='swiper-slide__header-block'>
                  <span class='swiper-slide__header-name'>{$review['name']}</span>
                </p>
              </div>
              <div class='swiper-slide__star-block star-one'>
                <span class='swiper-slide__star-one star'></span>
                <span class='swiper-slide__star-two star'></span>
                <span class='swiper-slide__star-three star'></span>
                <span class='swiper-slide__star-four star'></span>
                <span class='swiper-slide__star-five star'></span>
              </div>
                <p class='swiper-slide__comment'>
                  <span class='swiper-slide__comment-text'>{$review['text']}</span>
                </p>
              </li>
              ";
              }
              ?>
            </ul>
            <div class="reviews__swiper-pagination swiper-pagination"></div>
          </div>
        </div>
      </div>
    </section>

  </main>
  <?php include_once $docROOT . $path . '/files/php/layout/footer.php'; ?>
</body>

</html>