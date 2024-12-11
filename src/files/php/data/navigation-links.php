<?php
$navigationLinks = [
  ['name' => 'Главная', 'path' => "$path/index.php"],
  ['name' => 'Наши услуги', 'path' => "$path/files/php/pages/services/services-page.php"],
  ['name' => 'О нас', 'path' => "$path/files/php/pages/about/about.php"],
  ['name' => 'Парковочные системы', 'path' => "$path/files/php/pages/parking-systems/parking-systems.php"],
  ['name' => 'Автосигнализации', 'path' => "$path/files/php/pages/autosygnals/autosygnals.php"],
];

$navigationFooterLinks = [
  [
    'title' => 'Магазин',
    'link' => "$path/shop.php",
    'sub-title' => [
      'name' => 'Автосигнализации',
      'list' => [
        ['link' => "$path/alarm1.php", 'name' => 'Автосигнализация 1'],
        ['link' => "$path/alarm2.php", 'name' => 'Автосигнализация 2'],
        ['link' => "$path/alarm3.php", 'name' => 'Автосигнализация 3'],
        ['link' => "$path/alarm4.php", 'name' => 'Автосигнализация 4'],
        ['link' => "$path/alarm5.php", 'name' => 'Автосигнализация 5'],
      ]
    ]
  ],
  [
    'title' => 'Установочный центр',
    'link' => "$path/install_center.php",
    'sub-title' => [
      'name' => 'Услуги установки',
      'list' => [
        ['link' => "$path/service1.php", 'name' => 'Услуга 1'],
        ['link' => "$path/service2.php", 'name' => 'Услуга 2'],
        ['link' => "$path/service3.php", 'name' => 'Услуга 3']
      ]
    ]
  ],
  [
    'title' => 'Клиенту',
    'link' => "$path/client.php",
    'list' => [
      ['link' => "$path/client1.php", 'name' => 'Услуга 1'],
      ['link' => "$path/client2.php", 'name' => 'Услуга 2'],
      ['link' => "$path/client3.php", 'name' => 'Услуга 3'],
    ]
  ],
];

?>