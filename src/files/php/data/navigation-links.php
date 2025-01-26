<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';


class NavigationLinks
{

  private $path;
  private $variables;
  private $path_pages;


  public function __construct()
  {

    $this->variables = new SetVariables();
    $this->variables->setVar();
    $this->path = $this->variables->getPathFileURL();
    $this->path_pages = $this->variables->getPathFileURL() . "/files/php/pages";
  }

  public function getNavlinks()
  {
    $navigationLinks = [
      ['name' => 'Главная', 'path' => "$this->path/index.php"],
      ['name' => 'Автосигнализации', 'path' => "$this->path_pages/autosygnals/autosygnals.php"],
      ['name' => 'Парковочные системы', 'path' => "$this->path_pages/parking-systems/parking-systems.php"],
      ['name' => 'Наши услуги', 'path' => "$this->path_pages/service/service.php"],
      ['name' => 'О нас', 'path' => "$this->path_pages/about/about.php"],
      ['name' => 'Контакты', 'path' => "$this->path_pages/contacts/contacts.php"],
    ];
    return $navigationLinks;
  }

  public function getNavigationFooterLinks()
  {

    $navigationFooterLinks = [
      [
        'title' => 'Магазин',
        'list' => [
          [
            'name' => 'Автосигнализации',
            'children' => [
              ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=auto", 'name' => 'Автосигнализации с автозапуском'],
              ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=gsm", 'name' => 'Автосигнализации с GSM'],
              ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=no-auto", 'name' => 'Автосигнализации без автозапуска'],
              ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=catalog", 'name' => 'Каталог автосигнализаций Starline'],
              ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=accessories", 'name' => 'Пульты и аксессуары'],
              ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=parking-systems", 'name' => 'Парковочные системы'],
              ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=price", 'name' => 'Прайс на материал и установку'],
            ],
          ],
        ],
      ],
      [
        'title' => 'Установочный центр',
        'list' => [
          ['link' => "$this->path_pages/service/service.php?service=setup", 'name' => 'Установка и ремонт сигнализаций'],
          ['link' => "$this->path_pages/service/service.php?service=locks", 'name' => 'Ремонт центрозамков'],
          ['link' => "$this->path_pages/service/service.php?service=setup-media", 'name' => 'Установка автозвука и мультимедиа'],
          ['link' => "$this->path_pages/service/service.php?service=setup-system-parking", 'name' => 'Установка систем паркинга'],
          ['link' => "$this->path_pages/service/service.php?service=autoelectric", 'name' => 'Услуги автоэлектрика'],
          ['link' => "$this->path_pages/service/service.php?service=rus", 'name' => 'Русификация авто и чиптюнинг'],
          ['link' => "$this->path_pages/service/service.php?service=diagnostic", 'name' => 'Компьютерная диагностика'],
          ['link' => "$this->path_pages/service/service.php?service=disabled-autosynal", 'name' => 'Отключение сигнализации'],
          [
            'link' => "$this->path_pages/service/service.php?service=setup-videoregistration",
            'name' => "Установка видеорегистраторов и антирадаров"
          ],
          ['link' => "$this->path_pages/service/service.php?service=price", 'name' => "Прайс на услуги"],
        ],
      ],
      [
        'title' => "Клиенту",
        "list" => [
          ["link" => $this->path_pages . "/client/client.php?client=special", "name" => 'Специальные предложения'],
          ["link" => $this->path_pages . "/client/client.php?client=cart", "name" => 'Корзина заказа'],
          ["link" => $this->path_pages . "/client/client.php?client=review", "name" => 'Оставить отзыв'],
          ["link" => $this->path_pages . "/client/client.php?client=gallery", "name" => 'Галерея выполненных работ'],
          ["link" => $this->path_pages . "/client/client.php?client=map", "name" => 'Как к нам добраться'],
        ]
      ]
    ];
    return $navigationFooterLinks;
  }

  public function getCategoriesAutoSygnals()
  {
    $categories_autosygnals = [
      ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=auto", 'name' => 'Автосигнализации с автозапуском', "count" => 11, "src" => "$this->path/assets/images/autosygnals/autosygnals-1.avif"],
      ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=gsm", 'name' => 'Автосигнализации с GSM', "count" => 11, "src" => "$this->path/assets/images/autosygnals/autosygnals-2.avif"],
      ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=no-auto", 'name' => 'Автосигнализации без автозапуска', "count" => 11, "src" => "$this->path/assets/images/autosygnals/autosygnals-3.avif"],
      ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=catalog", 'name' => 'Каталог автосигнализаций Starline', "count" => 11, "src" => "$this->path/assets/images/autosygnals/autosygnals-4.avif"],
      ['link' => "$this->path_pages/autosygnals/autosygnals.php?auto=accessories", 'name' => 'Пульты и аксессуары', "count" => 11, "src" => "$this->path/assets/images/autosygnals/autosygnals-5.avif"],
    ];
    return $categories_autosygnals;
  }
}


?>