<?php

include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$quality_videos = [
  'title' => 'АВТОРИЗИРОВАННЫЙ ПАРТНЕР | STARLINE',
  'qualities' => [
    'ДОСТУПНАЯ СТОИМОСТЬ ПРОДУКЦИИ И УСЛУГ',
    'ПРЕДОСТАВЛЯЕМ КЛИЕНТАМ НАИЛУЧШЕЕ КАЧЕСТВО ТОВАРОВ И СЕРВИСА',
    'ИНДИВИДУАЛЬНЫЙ ПОДХОД К КАЖДОМУ КЛИЕНТУ',
  ],
  'videos' => [
    [
      'poster' => "$path/assets/images/video-images/poster-quality.avif",
      'srcMob' => "$path/assets/videos/video-quality-mob.webm",
      'src' => ["$path/assets/videos/video-quality.webm", "$path/assets/videos/video-quality.mp4"],
      'type' => ['video/webm', 'video/mp4'],
    ],
  ],
];


?>