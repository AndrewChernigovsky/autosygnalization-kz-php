<?php

include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$slides = [
  [
    'poster' => "$path/assets/images/video-images/poster-1.avif",
    'srcMob' => "$path/assets/videos/video-mob-1.webm",
    'src' => ["$path/assets/videos/video-1.webm", "$path/assets/videos/video-1.mp4"],
    'type' => ['video/webm', 'video/mp4'],
    'title' => 'АВТОСИГНАЛИЗАЦИИ АВТОЗАПУСК GSM GPS',
    'list' => ['ПРОДАЖА', 'УСТАНОВКА', 'РЕМОНТ'],
    'link' => "$path/files/php/pages/services/services-page.php",
  ],
  [
    'poster' => "$path/assets/images/video-images/poster-2.avif",
    'srcMob' => "$path/assets/videos/video-mob-2.webm",
    'src' => ["$path/assets/videos/video-2.webm", "$path/assets/videos/video-2.mp4"],
    'type' => ['video/webm', 'video/mp4'],
    'title' => 'РУСИФИКАЦИЯ АВТОМОБИЛЕЙ, ЧИПТЮНИНГ',
    'list' => ['ЛИЦЕНЗИОННЫЕ ПРОШИВКИ', 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА'],
    'link' => "$path/files/php/pages/parking-systems/parking-systems.php"
  ],
  [
    'poster' => "$path/assets/images/video-images/poster-3.avif",
    'srcMob' => "$path/assets/videos/video-mob-3.webm",
    'src' => ["$path/assets/videos/video-3.webm", "$path/assets/videos/video-3.mp4"],
    'type' => ['video/webm', 'video/mp4'],
    'title' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ',
    'list' => ['ПРОДАЖА', 'МОНТАЖ', 'ВОЗМОЖЕН ВЫЕЗД'],
    'link' => "$path/files/php/pages/autosygnals/autosygnals.php",
  ],
  [
    'poster' => "$path/assets/images/video-images/poster-4.avif",
    'srcMob' => "$path/assets/videos/video-mob-4.webm",
    'src' => ["$path/assets/videos/video-4.webm", "$path/assets/videos/video-4.mp4"],
    'type' => ['video/webm', 'video/mp4'],
    'title' => 'УСЛУГИ АВТОЭЛЕКТРИКА',
    'list' => [
      'РЕМОНТ ЦЕНТРОЗАМКОВ',
      'УСТАНОВКА СИСТЕМ ПАРКИНГА',
      'ОТКЛЮЧЕНИЕ АВТОСИГНАЛИЗАЦИЙ',
    ],
    'link' => "$path/files/php/pages/services/services-page.php",
  ],
];


?>