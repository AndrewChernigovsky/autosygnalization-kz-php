<?php

include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$slides = [
  [
    'poster' => "$path/assets/videos/poster1.avif", // Используем переменную $path для постера
    'srcMob' => "$path/assets/videos/video-mob--1.mp4", // Путь к мобильному видео
    'src' => ["$path/assets/videos/video-1.mp4", "$path/assets/videos/video-1.webm"], // Пути к видео
    'type' => ['video/mp4', 'video/webm'], // Типы видео
    'title' => 'АВТОСИГНАЛИЗАЦИИ АВТОЗАПУСК GSM GPS',
    'list' => ['ПРОДАЖА', 'УСТАНОВКА', 'РЕМОНТ'],
    'link' => "$path/files/php/pages/services/services-page.php",
  ],
  [
    'poster' => "$path/assets/videos/poster-2.avif",
    'srcMob' => "$path/assets/videos/video-mob-2.mp4",
    'src' => ["$path/assets/videos/video-2.mp4", "$path/assets/videos/video-2.webm"],
    'type' => ['video/mp4', 'video/webm'],
    'title' => 'РУССИФИКАЦИЯ АВТОМОБИЛЕЙ ЧИПТЮНИНГ',
    'list' => ['ЛЕЦИНЗИОННЫЕ ПРОШИВКИ', 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА'],
    'link' => "$path/files/php/pages/parking-systems/parking-systems.php"
  ],
  [
    'poster' => "$path/assets/videos/poster3.avif",
    'srcMob' => "$path/assets/videos/video-mob-3.mp4",
    'src' => ["$path/assets/videos/video-3.mp4", "$path/assets/videos/video-3.webm"],
    'type' => ['video/mp4', 'video/webm'],
    'title' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ',
    'list' => ['ПРОДАЖА', 'МОНТАЖ', 'ВОЗМОЖЕН ВЫЕЗД'],
    'link' => "$path/files/php/pages/autosygnals/autosygnals.php",
  ],
  [
    'poster' => "$path/assets/videos/poster4.avif",
    'srcMob' => "$path/assets/videos/video-mob-4.mp4",
    'src' => ["$path/assets/videos/video-4.mp4", "$path/assets/videos/video-4.webm"],
    'type' => ['video/mp4', 'video/webm'],
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