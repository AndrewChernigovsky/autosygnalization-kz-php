<?php

namespace SECTIONS;

use DATA\AdvantageVideoData;
use DATA\AdvantageData;

function qualitySection(): string
{
  // Получаем данные
  $qualityVideos = (new AdvantageVideoData())->getAllVideos();
  $advantageData = (new AdvantageData())->getAllAdvantage();
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
          <?php if (!empty($qualityVideos['videos'][0]['title_icon'])): ?>
            <img src="<?= htmlspecialchars($qualityVideos['videos'][0]['title_icon']); ?>" alt="Иконка Starline" width="240" height="40">
          <?php endif; ?>
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
      <h2 class="secondary-title">

        <?= "Наши преимущества"; ?>
      </h2>
      <div class="quality__list-wrapper">
        <div class="container">
          <ul class="quality__list list-style-none">
            <?php foreach ($advantageData as $index => $item): ?>
              <li class="quality__item<?= $index > 2 ? ' quality__item--hidden' : '' ?>">
                <?php if ($item['image_path']): ?>
                  <img src="<?= htmlspecialchars($item['image_path']); ?>" alt="<?= htmlspecialchars($item['content']); ?>">
                <?php endif; ?>
                <p><?= $item['content']; ?></p>
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
