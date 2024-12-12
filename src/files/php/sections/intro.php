<?php
include_once __DIR__ . '/../data/slides.php';
$viewportWidth = 768;
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
          <div class="swiper-slide">
            <video class="intro-video" preload="auto" autoplay loop muted
              poster="<?= htmlspecialchars($slide['poster']) ?>">
              <?php if ($viewportWidth < 768): ?>
                <source src="<?= htmlspecialchars($slide['srcMob']) ?>" data-src="<?= htmlspecialchars($slide['srcMob']) ?>"
                  type="video/mp4" />
              <?php else: ?>
                <?php foreach ($slide['src'] as $index => $source): ?>
                  <source src="<?= htmlspecialchars($source) ?>" data-src="<?= htmlspecialchars($source) ?>"
                    type="<?= htmlspecialchars($slide['type'][$index]) ?>" />
                <?php endforeach; ?>
              <?php endif; ?>
              Ваш браузер не поддерживает тег video.
            </video>

            <div class="container content">
              <h2 class="title"><?= htmlspecialchars($slide['title']) ?></h2>

              <ul class="list-slide list-style-none">
                <?php foreach ($slide['list'] as $item): ?>
                  <li>
                    <span class="advantages-text"><?= htmlspecialchars($item) ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
              <a href="<?php echo $slide['link'] ?>" class="y-button button link btn-4">Подробнее</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>