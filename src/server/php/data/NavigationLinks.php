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
      $query = "SELECT title as name, href as path FROM Navigation WHERE parent_id IS NULL AND is_active = 1 ORDER BY position ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();

      $navigationLinks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      if (empty($navigationLinks)) {
        return [
          ['name' => 'Главная', 'path' => "/"],
          ['name' => 'Автосигнализации', 'path' => "/autosygnals"],
          ['name' => 'Видеорегистраторы', 'path' => "/parking-systems?SELECT=name"],
          ['name' => 'Наши услуги', 'path' => "/services"],
          ['name' => 'О нас', 'path' => "/about"],
          ['name' => 'Контакты', 'path' => "/contacts"],
        ];
      }

      return $navigationLinks;
    } catch (\Exception $e) {
      error_log("Ошибка получения навигации: " . $e->getMessage());
      return [
        ['name' => 'Главная', 'path' => "/"],
        ['name' => 'Автосигнализации', 'path' => "/autosygnals"],
        ['name' => 'Видеорегистраторы', 'path' => "/parking-systems?SELECT=name"],
        ['name' => 'Наши услуги', 'path' => "/services"],
        ['name' => 'О нас', 'path' => "/about"],
        ['name' => 'Контакты', 'path' => "/contacts"],
      ];
    }
  }

  public function getNavigationFooterLinks()
  {

    $navigationFooterLinks = [
      [
        'title' => 'Магазин',
        'list' => [
          [
            //'name' => 'Автосигнализации',
            'children' => [
              ['link' => "/autosygnal?type=auto&SELECT=name", 'name' => 'Автосигнализации с автозапуском'],
              ['link' => "/autosygnal?type=gsm&SELECT=name", 'name' => 'Автосигнализации с GSM'],
              ['link' => "/autosygnal?type=without-auto&SELECT=name", 'name' => 'Автосигнализации без автозапуска'],
              ['link' => "/autosygnal?type=starline&SELECT=name", 'name' => 'Каталог автосигнализаций Starline'],
              ['link' => "/autosygnal?type=remote-controls&SELECT=name", 'name' => 'Пульты и аксессуары'],
              ['link' => "/parking-systems?SELECT=name", 'name' => 'Видеорегистраторы'],
              ['link' => "/price", 'name' => "Прайс на материал и установку"]
            ],
          ],
        ],
      ],
      [
        'title' => 'Установочный центр',
        'list' => [
          ['link' => "/service?service=setup", 'name' => 'Установка и ремонт сигнализаций'],
          ['link' => "/service?service=locks", 'name' => 'Ремонт центрозамков'],
          ['link' => "/service?service=setup-media", 'name' => 'Установка автозвука и мультимедиа'],
          ['link' => "/service?service=setup-system-parking", 'name' => 'Установка систем паркинга'],
          ['link' => "/service?service=autoelectric", 'name' => 'Услуги автоэлектрика'],
          ['link' => "/service?service=rus", 'name' => 'Русификация авто и чиптюнинг'],
          ['link' => "/service?service=diagnostic", 'name' => 'Компьютерная диагностика'],
          ['link' => "/service?service=disabled-autosynal", 'name' => 'Отключение сигнализации'],
          [
            'link' => "/service?service=setup-videoregistration",
            'name' => "Установка видеорегистраторов и антирадаров"
          ],
          ['link' => "/price", 'name' => "Прайс на услуги"],
        ],
      ],
      [
        'title' => "Клиенту",
        "list" => [
          ["link" => "/special", "name" => 'Специальные предложения'],
          ["link" => "/cart", "name" => 'Корзина заказа'],
          ["link" => "https://2gis.kz/almaty/geo/70000001027313872", "name" => 'Оставить отзыв'],
          ["link" => "https://drive.google.com/drive/folders/1gRjuirVES2pO6EMTNDrL5KNGC4RfBRPb", "name" => 'Архив выполненных работ'],
          ["link" => "/contacts#location", "name" => 'Как к нам добраться'],
          ["link" => "/sertificates", "name" => 'Наши сертификаты'],
          ["link" => "#", "name" => 'Оплата и доставка'],
        ]
      ]
    ];
    return $navigationFooterLinks;
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
