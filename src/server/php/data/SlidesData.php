<?php

namespace DATA;

class SlidesData
{
  public function getData(): array
  {
    return [
      [
        'poster' => "/client/images/video-images/poster-1.avif",
        'srcMob' => "/client/videos/video-mob-1.webm",
        'src' => ["/client/videos/video-1.webm", "/client/videos/video-1.mp4"],
        'type' => ['video/webm', 'video/mp4'],
        'title' => 'АВТОСИГНАЛИЗАЦИИ АВТОЗАПУСК GSM GPS',
        'list' => ['ПРОДАЖА', 'УСТАНОВКА', 'РЕМОНТ'],
        'link' => "/autosygnals",
      ],
      [
        'poster' => "/client/images/video-images/poster-2.avif",
        'srcMob' => "/client/videos/video-mob-2.webm",
        'src' => ["/client/videos/video-2.webm", "/client/videos/video-2.mp4"],
        'type' => ['video/webm', 'video/mp4'],
        'title' => 'РУСИФИКАЦИЯ АВТОМОБИЛЕЙ, ЧИПТЮНИНГ',
        'list' => ['ЛИЦЕНЗИОННЫЕ ПРОШИВКИ', 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА'],
        'link' => "/service?service=rus"
      ],
      [
        'poster' => "/client/images/video-images/poster-3.avif",
        'srcMob' => "/client/videos/video-mob-3.webm",
        'src' => ["/client/videos/video-3.webm", "/client/videos/video-3.mp4"],
        'type' => ['video/webm', 'video/mp4'],
        'title' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ',
        'list' => ['ПРОДАЖА', 'МОНТАЖ', 'ВОЗМОЖЕН ВЫЕЗД'],
        'link' => "/service?service=setup-videoregistration",
      ],
      [
        'poster' => "/client/images/video-images/poster-4.avif",
        'srcMob' => "/client/videos/video-mob-4.webm",
        'src' => ["/client/videos/video-4.webm", "/client/videos/video-4.mp4"],
        'type' => ['video/webm', 'video/mp4'],
        'title' => 'УСЛУГИ АВТОЭЛЕКТРИКА',
        'list' => [
          'РЕМОНТ ЦЕНТРОЗАМКОВ',
          'УСТАНОВКА СИСТЕМ ПАРКИНГА',
          'ОТКЛЮЧЕНИЕ АВТОСИГНАЛИЗАЦИЙ',
        ],
        'link' => "/service?service=autoelectric",
      ],
    ];

  }
}
