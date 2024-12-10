<?php
$viewportWidth = 768;

$distPath = $_SERVER['DOCUMENT_ROOT'] . '/dist';

// Проверяем, существует ли папка dist
if (is_dir($distPath)) {
  $currentUrl = "http://localhost:3000/dist/index.php";
  $pathFile = "http://localhost:3000/dist";
  $pathFile_URL = '/dist';
} else {
  $currentUrl = "/index.php";
  $pathFile = "";
  $pathFile_URL = '';
}

$slides = [
  [
    'poster' => "$pathFile_URL/assets/videos/poster1.avif", // Используем переменную $path для постера
    'srcMob' => "$pathFile_URL/assets/videos/video-mob--1.mp4", // Путь к мобильному видео
    'src' => ["$pathFile_URL/assets/videos/video-1.mp4", "$pathFile_URL/assets/videos/video-1.webm"], // Пути к видео
    'type' => ['video/mp4', 'video/webm'], // Типы видео
    'title' => 'АВТОСИГНАЛИЗАЦИИ АВТОЗАПУСК GSM GPS',
    'list' => ['ПРОДАЖА', 'УСТАНОВКА', 'РЕМОНТ'],
    'link' => "$pathFile_URL/files/php/pages/services/services-page.php",
  ],
  [
    'poster' => "$pathFile_URL/assets/videos/poster-2.avif",
    'srcMob' => "$pathFile_URL/assets/videos/video-mob-2.mp4",
    'src' => ["$pathFile_URL/assets/videos/video-2.mp4", "$pathFile_URL/assets/videos/video-2.webm"],
    'type' => ['video/mp4', 'video/webm'],
    'title' => 'РУССИФИКАЦИЯ АВТОМОБИЛЕЙ ЧИПТЮНИНГ',
    'list' => ['ЛЕЦИНЗИОННЫЕ ПРОШИВКИ', 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА'],
    'link' => "$pathFile_URL/files/php/pages/parking-systems/parking-systems.php"
  ],
  [
    'poster' => "$pathFile_URL/assets/videos/poster3.avif",
    'srcMob' => "$pathFile_URL/assets/videos/video-mob-3.mp4",
    'src' => ["$pathFile_URL/assets/videos/video-3.mp4", "$pathFile_URL/assets/videos/video-3.webm"],
    'type' => ['video/mp4', 'video/webm'],
    'title' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ',
    'list' => ['ПРОДАЖА', 'МОНТАЖ', 'ВОЗМОЖЕН ВЫЕЗД'],
    'link' => "$pathFile_URL/files/php/pages/autosygnals/autosygnals.php",
  ],
  [
    'poster' => "$pathFile_URL/assets/videos/poster4.avif",
    'srcMob' => "$pathFile_URL/assets/videos/video-mob-4.mp4",
    'src' => ["$pathFile_URL/assets/videos/video-4.mp4", "$pathFile_URL/assets/videos/video-4.webm"],
    'type' => ['video/mp4', 'video/webm'],
    'title' => 'УСЛУГИ АВТОЭЛЕКТРИКА',
    'list' => [
      'РЕМОНТ ЦЕНТРОЗАМКОВ',
      'УСТАНОВКА СИСТЕМ ПАРКИНГА',
      'ОТКЛЮЧЕНИЕ АВТОСИГНАЛИЗАЦИЙ',
    ],
    'link' => "$pathFile_URL/files/php/pages/services/services-page.php",
  ],
];
?>
<style>
  .title {
    opacity: 0;
    transform: translateY(-20px);
    /* Сдвиг вниз для эффекта появления */
    transition: opacity 0.5s ease, transform 0.5s ease;
    /* Плавная анимация */
  }

  .title.visible {
    opacity: 1;
    transform: translateY(0);
    /* Возвращаем на место */
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