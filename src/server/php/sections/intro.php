<?php
include_once __DIR__ . '/../data/slides.php';
?>
<style>
  .title {
    opacity: 0;
    transform: translateY(-20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
  }

  .title.visible {
    opacity: 1;
    transform: translateY(0);
  }
</style>
<section class="intro">
  <div class="intro__wrapper">
    <div class="swiper swiper-intro">
      <div class="swiper-wrapper">
        <?php foreach ($slides as $videoIndex => $slide): ?>
          <div class="swiper-slide swiper-intro__slide">
            <video class="intro__video" preload="auto" autoplay loop muted
              poster="<?= htmlspecialchars($slide['poster']) ?>">
              <source src="<?= htmlspecialchars($slide['srcMob']) ?>" data-src="<?= htmlspecialchars($slide['srcMob']) ?>"
                type="<?= htmlspecialchars($slide['type'][0]) ?>" media="(max-width:768px)" />
              <?php foreach ($slide['src'] as $index => $source): ?>
                <source src="<?= htmlspecialchars($source) ?>" data-src="<?= htmlspecialchars($source) ?>"
                  type="<?= htmlspecialchars($slide['type'][$index]) ?>" />
              <?php endforeach; ?>
              Ваш браузер не поддерживает тег video.
            </video>


            <div class="container intro__content">
              <h2 class="intro__title visible"><?= htmlspecialchars($slide['title']) ?></h2>

              <ul class="intro__list-slide list-style-none">
                <?php foreach ($slide['list'] as $item): ?>
                  <li>
                    <span class="intro__advantages-text"><?= htmlspecialchars($item) ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
              <a href="<?php echo $slide['link'] ?>" class="intro__link y-button button link">Подробнее</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>