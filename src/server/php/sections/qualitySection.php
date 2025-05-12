<?php

namespace SECTIONS;

use DATA\QualityVideos;

function qualitySection(): string
{
  // Получаем данные
  $qualityVideos = (new QualityVideos())->getData();

  // Генерация HTML
  ob_start();
  ?>
  <section class="quality" id="quality">
    <?php foreach ($qualityVideos['videos'] as $video): ?>
      <video class="quality__video quality__video--bg" preload="auto" autoplay loop muted
        poster="<?= htmlspecialchars($video['poster']); ?>">
        <source src="<?= htmlspecialchars($video['srcMob']); ?>" data-src="<?= htmlspecialchars($video['srcMob']); ?>"
          type="<?= htmlspecialchars($video['type'][0]); ?>" media="(max-width:768px)" />
        <?php foreach ($video['src'] as $index => $source): ?>
          <source src="<?= htmlspecialchars($source); ?>" data-src="<?= htmlspecialchars($source); ?>"
            type="<?= htmlspecialchars($video['type'][$index]); ?>" />
        <?php endforeach; ?>
        Ваш браузер не поддерживает тег video.
      </video>
    <?php endforeach; ?>

    <div class="quality__present">
      <h2>
        <p>
          <span>
            <?= htmlspecialchars($qualityVideos['title']); ?>
          </span>
        </p>
      </h2>
    </div>

    <?php foreach ($qualityVideos['videos'] as $video): ?>
      <video class="quality__video" preload="auto" autoplay loop muted poster="<?= htmlspecialchars($video['poster']); ?>">
        <source src="<?= htmlspecialchars($video['srcMob']); ?>" data-src="<?= htmlspecialchars($video['srcMob']); ?>"
          type="<?= htmlspecialchars($video['type'][0]); ?>" media="(max-width:768px)" />
        <?php foreach ($video['src'] as $index => $source): ?>
          <source src="<?= htmlspecialchars($source); ?>" data-src="<?= htmlspecialchars($source); ?>"
            type="<?= htmlspecialchars($video['type'][$index]); ?>" />
        <?php endforeach; ?>
        Ваш браузер не поддерживает тег video.
      </video>
    <?php endforeach; ?>
    <div class="quality__present">
      <h2>

        <?= "Наши преимущества"; ?>
      </h2>
      <div class="quality__list-wrapper">
        <div class="container">
          <ul class="quality__list list-style-none">
            <?php foreach ($qualityVideos['qualities'] as $index => $item): ?>
              <li class="quality__item<?= $index > 2 ? ' quality__item--hidden' : '' ?>"><?= htmlspecialchars($item); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
