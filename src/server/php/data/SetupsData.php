<?php

namespace DATA;

class SetupsData
{
  public function getData(): array
  {
    return [
      "shop" => [
        "title" => 'Магазин',
        "descs" => [
          'Наилучшие предложения автоэлектроники в нашем магазине! Выбирайте, что Вас интересует!',
        ],
        "src" => [
          "mob" => "/client/images/setup/shop-img.avif",
          "desktop" => "/client/images/setup/shop-img.avif",
        ],
        "url" => "/catalog?SELECT=name&PAGE=1",
        "link-text" => "В магазин"
      ],
      "setup" => [
        "title" => 'Установочный центр',
        "descs" => [
          'Воспользуйтесь услугами нашего сервисного центра',
        ],
        "src" => [
          "mob" => "/client/images/setup/setup-img.avif",
          "desktop" => "/client/images/setup/setup-img.avif",
        ],
        "url" => "/services",
        "link-text" => "В раздел"
      ],
    ];
  }
}
