<?php
$path = '/dist/assets/videos';
$viewportWidth = 768;
$slides = [
  [
    'poster' => "$path/poster1.jpg", // Используем переменную $path для постера
    'srcMob' => "$path/video-mob--1.mp4", // Путь к мобильному видео
    'src' => ["$path/video-1.mp4", "$path/video-1.webm"], // Пути к видео
    'type' => ['video/mp4', 'video/webm'], // Типы видео
    'title' => 'АВТОСИГНАЛИЗАЦИИ АВТОЗАПУСК GSM GPS',
    'list' => ['ПРОДАЖА', 'УСТАНОВКА', 'РЕМОНТ'],
  ],
  [
    'poster' => "$path/poster-2.jpg",
    'srcMob' => "$path/video-mob-2.mp4",
    'src' => ["$path/video-2.mp4", "$path/video-2.webm"],
    'type' => ['video/mp4', 'video/webm'],
    'title' => 'РУССИФИКАЦИЯ АВТОМОБИЛЕЙ ЧИПТЮНИНГ',
    'list' => ['ЛЕЦИНЗИОННЫЕ ПРОШИВКИ', 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА'],
  ],
  [
    'poster' => "$path/poster3.jpg",
    'srcMob' => "$path/video-mob-3.mp4",
    'src' => ["$path/video-3.mp4", "$path/video-3.webm"],
    'type' => ['video/mp4', 'video/webm'],
    'title' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ',
    'list' => ['ПРОДАЖА', 'МОНТАЖ', 'ВОЗМОЖЕН ВЫЕЗД'],
  ],
  [
    'poster' => "$path/poster4.jpg",
    'srcMob' => "$path/video-mob-4.mp4",
    'src' => ["$path/video-4.mp4", "$path/video-4.webm"],
    'type' => ['video/mp4', 'video/webm'],
    'title' => 'УСЛУГИ АВТОЭЛЕКТРИКА',
    'list' => [
      'РЕМОНТ ЦЕНТРОЗАМКОВ',
      'УСТАНОВКА СИСТЕМ ПАРКИНГА',
      'ОТКЛЮЧЕНИЕ АВТОСИГНАЛИЗАЦИЙ',
    ],
  ],
];
?>
<section class="intro">
  <div class="intro__wrapper">
    <div class="swiper swiper-intro">
      <div class="swiper-wrapper">
        <?php foreach ($slides as $videoIndex => $slide): ?>
          <div class="swiper-slide">
            <video preload="auto" autoplay loop muted poster="<?= htmlspecialchars($slide['poster']) ?>">
              <?php if ($viewportWidth < 768): ?>
                <source src="<?= htmlspecialchars($slide['srcMob']) ?>" type="video/mp4" />
              <?php else: ?>
                <?php foreach ($slide['src'] as $index => $source): ?>
                  <source src="<?= htmlspecialchars($source) ?>" type="<?= htmlspecialchars($slide['type'][$index]) ?>" />
                <?php endforeach; ?>
              <?php endif; ?>
              Ваш браузер не поддерживает тег video.
            </video>

            <div class="container content">
              <h2><?= htmlspecialchars($slide['title']) ?></h2>

              <ul class="list-slide list-style-none">
                <?php foreach ($slide['list'] as $item): ?>
                  <li>
                    <span class="advantages-text"><?= htmlspecialchars($item) ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>

              <div class="button">
                <a href="#" class="y-button">Подробнее</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>