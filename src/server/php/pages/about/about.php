<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use function AUTH\SESSIONS\initSession;
use DATA\AboutusData;
use LAYOUT\Footer;
use LAYOUT\Head;
use LAYOUT\Header;
use COMPONENTS\ModalForm;
use function FUNCTIONS\renderPhoneButton;

initSession();

$title = 'О нас | Auto Security';
$header = new Header();
$footer = new Footer();
$head = new Head($title, [], []);
$aboutUs = new AboutusData();
$data = $aboutUs->getAllAboutUs();
$content = [];
foreach ($data as $item) {
    $content[$item['type']][] = $item;
}
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
            <div class="present__main-slogan-block">
              <?php if (!empty($content['present-slogan-block'][0]['content'])): ?>
                <?= $content['present-slogan-block'][0]['content'] ?>
              <?php endif; ?>
            </div>
            <div class="present__main-text">
              <?php if (!empty($content['present-text-block'][0]['content'])): ?>
                <?= $content['present-text-block'][0]['content'] ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="present__block">
            <h3 class="present__list-title">Наши преимущества</h3>
            <div class="present__list list-style-none">
                <?php if (!empty($content['advantages-list'][0]['content'])): ?>
                  <?= $content['advantages-list'][0]['content'] ?>
                <?php endif; ?>
            </div>
            <div class="present__list-comment">
              <?php if (!empty($content['comment-block'][0]['content'])): ?>
                <?= $content['comment-block'][0]['content'] ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="about-us__tech-photo tech-photo">
          <h3 class="tech-photo__title">Фотографии тех центра</h3>
          <div class="tech-photo__swiper swiper swiper-tech-photo">
            <div class="tech-photo__swiper-wrapper swiper-wrapper">
              <?php foreach ($content['tech-photo-image'] ?? [] as $item): ?>
                <div class='tech-photo__swiper-slide swiper-slide'>
                  <img src='<?= htmlspecialchars($item['image_path']) ?>' alt='картинка' width='600' height='300'>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="tech-photo__swiper-pagination swiper-pagination"></div>
          </div>
          <div class="tech-photo__block">
            <h3 class="tech-photo__block-title">Обращайтесь в Auto Security</h3>
            <?php if (!empty($content['appeal-text-block'][0]['content'])): ?>
              <?= $content['appeal-text-block'][0]['content'] ?>
            <?php endif; ?>
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
  <?= (new ModalForm())->render(); ?>
  <?= renderPhoneButton(); ?>
</body>

</html>