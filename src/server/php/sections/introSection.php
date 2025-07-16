<?php

namespace SECTIONS;

use DATABASE\InitDataBase;

function introSection()
{
  // Fetch slides data from database
  try {
    $db = new InitDataBase();
    $stmt = $db->prepare("SELECT id, video_filename, video_path, title, advantages, button_text, button_link FROM Videos_intro_slider WHERE is_active = TRUE ORDER BY created_at DESC");
    $stmt->execute();
    $videos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $slides = array_map(function ($video) {
      return [
        'poster' => $video['video_path'],
        'srcMob' => $video['video_path'],
        'src' => [$video['video_path']],
        'type' => ['video/mp4'],
        'title' => $video['title'],
        'list' => json_decode($video['advantages'], true) ?: [],
        'link' => $video['button_link']
      ];
    }, $videos);
  } catch (\Exception $e) {
    error_log('Ошибка получения слайдов из базы данных: ' . $e->getMessage());
    $slides = [];
  }

  // Validate slides data
  if (empty($slides) || !is_array($slides)) {
    return '<p>Слайды недоступны.</p>';
  }

  ob_start(); // Start output buffering
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
                poster="<?= htmlspecialchars($slide['poster'] ?? '') ?>">
                <?php if (!empty($slide['srcMob'])): ?>
                  <source src="<?= htmlspecialchars($slide['srcMob']) ?>" data-src="<?= htmlspecialchars($slide['srcMob']) ?>"
                    type="<?= htmlspecialchars($slide['type'][0] ?? '') ?>" media="(max-width:768px)" />
                <?php endif; ?>
                <?php foreach ($slide['src'] ?? [] as $index => $source): ?>
                  <source src="<?= htmlspecialchars($source) ?>" data-src="<?= htmlspecialchars($source) ?>"
                    type="<?= htmlspecialchars($slide['type'][$index] ?? '') ?>" />
                <?php endforeach; ?>
                Ваш браузер не поддерживает тег video.
              </video>

              <div class="container intro__content">
                <h2 class="intro__title visible secondary-title"><?= htmlspecialchars($slide['title'] ?? '') ?></h2>

                <ul class="intro__list-slide list-style-none">
                  <?php foreach ($slide['list'] ?? [] as $item): ?>
                    <li>
                      <span class="intro__advantages-text"><?= htmlspecialchars($item) ?></span>
                    </li>
                  <?php endforeach; ?>
                  <li>
                    <a href="<?= htmlspecialchars($slide['link'] ?? '#') ?>" class="intro__link y-button button link">
                      Подробнее
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean(); // Return the buffered content
}
