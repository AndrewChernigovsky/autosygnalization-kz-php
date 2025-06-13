<?php

namespace DATA;

class QualityVideos
{
  public function getData()
  {
    return [
      'title' => 'Auto Security - АВТОРИЗИРОВАННЫЙ ПАРТНЕР STARLINE',
      'qualities' => [
        'ДОСТУПНАЯ СТОИМОСТЬ ПРОДУКЦИИ И УСЛУГ',
        'ПРЕДОСТАВЛЯЕМ КЛИЕНТАМ НАИЛУЧШЕЕ КАЧЕСТВО ТОВАРОВ И СЕРВИСА',
        'ИНДИВИДУАЛЬНЫЙ ПОДХОД К КАЖДОМУ КЛИЕНТУ',
        'Большой опыт работы',
        'Гарантия на все товары/услуги',
      ],
      'videos' => [
        [
          'poster' => "/client/images/video-images/poster-quality.avif",
          'srcMob' => "/client/videos/video-quality-mob.webm",
          'src' => ["/client/videos/video-quality.webm", "/client/videos/video-quality.mp4"],
          'type' => ['video/webm', 'video/mp4'],
        ],
      ],
    ];
  }
}
