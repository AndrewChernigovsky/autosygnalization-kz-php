<?php

include_once __DIR__ . '/../helpers/classes/setVariables.php';



class NavigationLinks
{
    private $path;
    private $variables;
    private $path_pages;
    private $filters_products_count = [
      'vnedorojnik' => 10,
    ]; // тут добавил

    public function __construct(array $filters_products_count = []) //тут изменил
    {

        $this->variables = new SetVariables();
        $this->variables->setVar();
        $this->path = $this->variables->getPathFileURL();
        $this->path_pages = $this->variables->getPathFileURL() . "/files/php/pages";

        $this->filters_products_count = !empty($filters_products_count) ? $filters_products_count : [
          'vnedorojnik' => 10,
      ]; // тут добавил
    }

    public function getNavlinks()
    {
        $navigationLinks = [
          ['name' => 'Главная', 'path' => "/"],
          ['name' => 'Автосигнализации', 'path' => "/autosygnals"],
          ['name' => 'Видеорегистраторы', 'path' => "/parking-systems?SELECT=name"],
          ['name' => 'Наши услуги', 'path' => "/service"],
          ['name' => 'О нас', 'path' => "/about"],
          ['name' => 'Контакты', 'path' => "/contacts"],
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
                //'name' => 'Автосигнализации',
                'children' => [
                  ['link' => "/autosygnals-auto?SELECT=name", 'name' => 'Автосигнализации с автозапуском'],
                  ['link' => "/autosygnals-gsm?SELECT=name", 'name' => 'Автосигнализации с GSM'],
                  ['link' => "/autosygnals-without-auto?SELECT=name", 'name' => 'Автосигнализации без автозапуска'],
                  ['link' => "/autosygnals-starline?SELECT=name", 'name' => 'Каталог автосигнализаций Starline'],
                  ['link' => "/autosygnals-acssesuars?SELECT=name", 'name' => 'Пульты и аксессуары'],
                  ['link' => "/parking-systems?SELECT=name", 'name' => 'Видеорегистраторы'],
                  ['link' => "/price", 'name' => "Прайс на материал и установку"]
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
              ['link' => "/price", 'name' => "Прайс на услуги"],
            ],
          ],
          [
            'title' => "Клиенту",
            "list" => [
              ["link" => $this->path_pages . "/special", "name" => 'Специальные предложения'],
              ["link" => $this->path_pages . "/cart", "name" => 'Корзина заказа'],
              ["link" => "https://2gis.kz/almaty/geo/70000001027313872", "name" => 'Оставить отзыв'],
              ["link" => "https://drive.google.com/drive/folders/1gRjuirVES2pO6EMTNDrL5KNGC4RfBRPb", "name" => 'Галерея выполненных работ'],
              ["link" => $this->path_pages . "/contacts/contacts.php#location", "name" => 'Как к нам добраться'],
            ]
          ]
        ];
        return $navigationFooterLinks;
    }

    public function getCategoriesAutoSygnals()
    {
        $categories_autosygnals = [
          [
              'link' => "/autosygnals-auto?SELECT=name",
              'name' => 'Автосигнализации с автозапуском',
              'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
              'src' => "$this->path/assets/images/autosygnals/autosygnals-1.avif"
          ],
          [
              'link' => "/autosygnals-gsm?SELECT=name",
              'name' => 'Автосигнализации с GSM',
              'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
              'src' => "$this->path/assets/images/autosygnals/autosygnals-2.avif"
          ],
          [
              'link' => "/autosygnals-without-auto?SELECT=name",
              'name' => 'Автосигнализации без автозапуска',
              'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
              'src' => "$this->path/assets/images/autosygnals/autosygnals-3.avif"
          ],
          [
              'link' => "/autosygnals-starline?SELECT=name",
              'name' => 'Каталог автосигнализаций Starline',
              'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
              'src' => "$this->path/assets/images/autosygnals/autosygnals-4.avif"
          ],
          [
              'link' => "/autosygnals-acssesuars?SELECT=name",
              'name' => 'Пульты и аксессуары',
              'count' => $this->filters_products_count['vnedorojnik'] ?? 0,
              'src' => "$this->path/assets/images/autosygnals/autosygnals-5.avif"
          ],
    ];
        return $categories_autosygnals;
    }
}
