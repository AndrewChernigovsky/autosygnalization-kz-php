<?php
$page_path = $path . '/files/php/pages';
$page_autosyngals = $page_path . '/autosygnals';
$page_setup = $page_path . '/service';
$page_client = $page_path . '/client';
$navigationLinks = [
  ['name' => 'Главная', 'path' => "$path/index.php"],
  ['name' => 'Наши услуги', 'path' => "$page_path/files/php/pages/services/services-page.php"],
  ['name' => 'О нас', 'path' => "$page_path/files/php/pages/about/about.php"],
  ['name' => 'Парковочные системы', 'path' => "$page_path/files/php/pages/autosygnals/autosygnals.php?auto=parking-systems"],
  ['name' => 'Автосигнализации', 'path' => "$page_path/files/php/pages/autosygnals/autosygnals.php"],
];

$navigationFooterLinks = [
  [
    'title' => 'Магазин',
    'list' => [
      [
        'name' => 'Автосигнализации',
        'children' => [
          ['link' => "$page_autosyngals/autosygnals.php?auto=auto", 'name' => 'Автосигнализации с автозапуском'],
          ['link' => "$page_autosyngals/autosygnals.php?auto=gsm", 'name' => 'Автосигнализации с GSM'],
          ['link' => "$page_autosyngals/autosygnals.php?auto=no-auto", 'name' => 'Автосигнализации без автозапуска'],
          ['link' => "$page_autosyngals/autosygnals.php?auto=catalog", 'name' => 'Каталог автосигнализаций Starline'],
          ['link' => "$page_autosyngals/autosygnals.php?auto=accessories", 'name' => 'Пульты и аксессуары'],
          ['link' => "$page_autosyngals/autosygnals.php?auto=parking-systems", 'name' => 'Парковочные системы'],
          ['link' => "$page_autosyngals/autosygnals.php?auto=price", 'name' => 'Прайс на материал и установку'],
        ]
      ],
    ]
  ],
  [
    'title' => 'Установочный центр',
    'list' => [
      ['link' => "$page_setup/service.php?service=setup", 'name' => 'Установка и ремонт сигнализаций'],
      ['link' => "$page_setup/service.php?service=locks", 'name' => 'Ремонт центрозамков'],
      ['link' => "$page_setup/service.php?service=setup-media", 'name' => 'Установка автозвука и мультимедиа'],
      ['link' => "$page_setup/service.php?service=setup-system-parking", 'name' => 'Установка систем паркинга'],
      ['link' => "$page_setup/service.php?service=autoelectric", 'name' => 'Услуги автоэлектрика'],
      ['link' => "$page_setup/service.php?service=rus", 'name' => 'Русификация авто и чиптюнинг'],
      ['link' => "$page_setup/service.php?service=diagnostic", 'name' => 'Компьютерная диагностика'],
      ['link' => "$page_setup/service.php?service=disabled-autosynal", 'name' => 'Отключение сигнализации'],
      [
        'link' => "$page_setup/service.php?service=setup-videoregistration",
        'name' => 'Установка видеорегистраторов
и антирадаров'
      ],
      ['link' => "$page_setup/service.php?service=price", 'name' => 'Прайс на услуги'],
    ],
  ],
  [
    'title' => 'Клиенту',
    'list' => [
      ['link' => "$page_client/client.php?client=special", 'name' => 'Специальные предложения'],
      ['link' => "$page_client/client.php?client=cart", 'name' => 'Корзина заказа'],
      ['link' => "$page_client/client.php?client=review", 'name' => 'Оставить отзыв'],
      ['link' => "$page_client/client.php?client=gallery", 'name' => 'Галерея выполненных работ'],
      ['link' => "$page_client/client.php?client=map", 'name' => 'Как к нам добраться'],
    ]
  ],
];


?>