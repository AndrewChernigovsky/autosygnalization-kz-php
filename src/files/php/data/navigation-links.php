<?php
$page_path = $path . '/files/php/pages';
$page_autosyngals = $page_path . '/autosygnals';
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
      ['link' => "$page_path/alarm2.php", 'name' => 'Автосигнализации с GSM'],
      ['link' => "$page_path/alarm3.php", 'name' => 'Автосигнализации без автозапуска'],
      ['link' => "$page_path/alarm4.php", 'name' => 'Автосигнализация 4'],
      ['link' => "$page_path/alarm5.php", 'name' => 'Автосигнализация 5'],
    ],
  ],
  [
    'title' => 'Магазин',
    'list' => [
      [
        'name' => 'Услуги установки',
        'children' => [
          [
            'name' => 'Услуги установки',
            'children' => [
              ['link' => "$page_path/service1.php", 'name' => 'Услуга 1'],
              ['link' => "$page_path/service2.php", 'name' => 'Услуга 2'],
              ['link' => "$page_path/service3.php", 'name' => 'Услуга 3']
            ]
          ],
          ['link' => "$page_path/service2.php", 'name' => 'Услуга 2'],
          ['link' => "$page_path/service3.php", 'name' => 'Услуга 3']
        ]
      ],
      ['link' => "$page_path/alarm2.php", 'name' => 'Автосигнализации с GSM'],
      ['link' => "$page_path/alarm3.php", 'name' => 'Автосигнализации без автозапуска'],
      ['link' => "$page_path/alarm4.php", 'name' => 'Автосигнализация 4'],
      ['link' => "$page_path/alarm5.php", 'name' => 'Автосигнализация 5'],
    ]
  ],
];


?>