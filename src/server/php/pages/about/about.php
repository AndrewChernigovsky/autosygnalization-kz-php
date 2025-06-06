<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use function AUTH\SESSIONS\initSession;
use DATA\AboutusData;
use LAYOUT\Footer;
use LAYOUT\Head;
use LAYOUT\Header;

initSession();

$title = 'О нас | Auto Security';
$header = new Header();
$footer = new Footer();
$head = new Head($title, [], []);
$aboutUs = new AboutusData();
?>


<!DOCTYPE html>
<html lang="ru">
<?= $head->setHead(); ?>

<body>
  <?= $header->getHeader(); ?>
  <main class="main">
    <section class="about-us">
      <div class="container">
        <div class="about-us__present present">
          <div class="present__block">
            <h2 class="present__main-title">О нас</h2>
            <p class="present__main-slogan">“Auto Security” – установочный центр автоэлектроники.</p>
            <p class="present__main-slogan">Мы предлагаем лучшее!</p>
            <div class="present__main-text">
              <p>Наша компания была основана в 2004&nbsp;году, в самый расцвет автосервисов.</p>
              <p>Миссия нашей компании – предлагать качественные услуги в сфере установки и ремонта автоэлектроники.</p>
            </div>
          </div>
          <div class="present__block">
            <h3 class="present__list-title">Наши преимущества</h3>
            <ul class="present__list list-style-none">
              <li class="present__list-item">Наши мастера имеют богатый опыт по инсталляции разнообразного электронного
                оборудования на различные автомобили.</li>
              <li class="present__list-item">Мы постоянно повышаем свою квалификацию, участвуем в конференциях.</li>
              <li class="present__list-item">Аккуратность и ответственность – именно это сегодня является важными
                отличиями команды "Auto Security".</li>
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
          <h3 class="tech-photo__title">Фотографии тех центра</h3>
          <div class="tech-photo__swiper swiper swiper-tech-photo">
            <div class="tech-photo__swiper-wrapper swiper-wrapper">
              <?php
              foreach ($aboutUs->getDataImages() as $image) {
                echo "<div class='tech-photo__swiper-slide swiper-slide'>
                  <img src='{$image['src']}' alt='картинка' width='600' height='300'>
                </div>";
              }
              ?>
            </div>
            <div class="tech-photo__swiper-pagination swiper-pagination"></div>
          </div>
          <div class="tech-photo__block">
            <h3 class="tech-photo__block-title">Обращайтесь в Auto Security</h3>
            <p class="tech-photo__text">Вам будет оказана квалифицированная помощь по установке дополнительного
              электронного оборудования на Ваш автомобиль!</p>
            <p class="tech-photo__text">Мы продиагностируем Ваш авто, отремонтируем, установим, настроим Ваше
              оборудование! Доверяйте профессионалам!</p>
          </div>

        </div>
        <div class="about-us__reviews reviews">
          <h3 class="reviews__title">Отзывы наших клиентов</h3>
          <div class="reviews__swiper swiper swiper-reviews">
            <ul class="reviews__swiper-wrapper swiper-wrapper list-style-none">
              <?php
              foreach ($aboutUs->getData() as $review) {
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
              <div class='swiper-slide__star-block star star-5'>

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
  <?= $footer->getFooter(); ?>
</body>

</html>