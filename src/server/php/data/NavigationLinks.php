<?php

namespace DATA;

use DATABASE\DataBase;

class NavigationLinks extends DataBase
{
  private $filters_products_count = [
    'vnedorojnik' => 10,
  ];

  protected $pdo;

  public function __construct(array $filters_products_count = [])
  {
    $this->filters_products_count = !empty($filters_products_count) ? $filters_products_count : [
      'vnedorojnik' => 10,
    ];

    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  public function getNavlinks()
  {
    try {
      $query = "SELECT title as name, link as path, content, sort_order FROM Navigation WHERE on_page = 1 ORDER BY sort_order ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();

      $navigationLinks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      if (empty($navigationLinks)) {
        return [
          ['name' => 'Главная', 'path' => "/", 'content' => ''],
          ['name' => 'Автосигнализации', 'path' => "/autosygnals", 'content' => ''],
          ['name' => 'Видеорегистраторы', 'path' => "/parking-systems?SELECT=name", 'content' => ''],
          ['name' => 'Наши услуги', 'path' => "/services", 'content' => ''],
          ['name' => 'О нас', 'path' => "/about", 'content' => ''],
          ['name' => 'Контакты', 'path' => "/contacts", 'content' => ''],
        ];
      }

      return $navigationLinks;
    } catch (\Exception $e) {
      error_log("Ошибка получения навигации: " . $e->getMessage());
      return [
        ['name' => 'Главная', 'path' => "/", 'content' => ''],
        ['name' => 'Автосигнализации', 'path' => "/autosygnals", 'content' => ''],
        ['name' => 'Видеорегистраторы', 'path' => "/parking-systems?SELECT=name", 'content' => ''],
        ['name' => 'Наши услуги', 'path' => "/services", 'content' => ''],
        ['name' => 'О нас', 'path' => "/about", 'content' => ''],
        ['name' => 'Контакты', 'path' => "/contacts", 'content' => ''],
      ];
    }
  }

  public function getNavigationFooterLinks()
  {
    try {
      $query = "SELECT section, name, link, position FROM Footer WHERE visible = 1 ORDER BY section, position ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();

      $links = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      if (empty($links)) {
        return []; // Если в футере пусто, возвращаем пустой массив
      }

      // Группируем ссылки по секциям
      $groupedLinks = [];
      foreach ($links as $link) {
        $groupedLinks[$link['section']][] = [
          'link' => $link['link'],
          'name' => $link['name']
        ];
      }

      // Формируем финальную структуру, которую ожидает GenerateFooterLinks.php
      $navigationFooterLinks = [];
      
      $sectionTitles = [
        'shop' => 'Магазин',
        'installation' => 'Установочный центр',
        'client' => 'Клиенту'
      ];

      foreach ($sectionTitles as $sectionKey => $sectionTitle) {
          if(isset($groupedLinks[$sectionKey])) {
              $children = $groupedLinks[$sectionKey];
              // "Магазин" имеет дополнительный уровень вложенности 'children'
              if ($sectionKey === 'shop') {
                $navigationFooterLinks[] = [
                    'title' => $sectionTitle,
                    'list' => [
                        [
                            'children' => $children
                        ]
                    ]
                ];
              } else {
                 $navigationFooterLinks[] = [
                    'title' => $sectionTitle,
                    'list' => $children
                ];
              }
          }
      }

      return $navigationFooterLinks;

    } catch (\Exception $e) {
      error_log("Ошибка получения ссылок для футера: " . $e->getMessage());
      return []; // В случае ошибки возвращаем пустой массив
    }
  }

  public function getCategoriesAutoSygnals()
  {
    $categories_autosygnals = [
      [
        'type' => "auto",
        'link' => "/autosygnal?SELECT=name&type=auto",
        'name' => 'Автосигнализации с автозапуском',
        'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
        'src' => "/client/images/autosygnals/autosygnals-1.avif"
      ],
      [
        'type' => "gsm",
        'link' => "/autosygnal?SELECT=name&type=gsm",
        'name' => 'Автосигнализации с GSM',
        'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
        'src' => "/client/images/autosygnals/autosygnals-2.avif"
      ],
      [
        'type' => "without-auto",
        'link' => "/autosygnal?SELECT=name&ype=without-auto",
        'name' => 'Автосигнализации без автозапуска',
        'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
        'src' => "/client/images/autosygnals/autosygnals-3.avif"
      ],
      [
        'type' => "starline",
        'link' => "/autosygnal?SELECT=name&type=starline",
        'name' => 'Каталог автосигнализаций Starline',
        'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
        'src' => "/client/images/autosygnals/autosygnals-4.avif"
      ],
      [
        'type' => "acssesuars",
        'link' => "/autosygnal?SELECT=name&type=remote-controls",
        'name' => 'Пульты и аксессуары',
        'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
        'src' => "/client/images/autosygnals/autosygnals-5.avif"
      ],
    ];
    return $categories_autosygnals;
  }
}
