<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

/* $tabs = [
  [
    "id" => "product_keychain_a93-eco",
    'title' => 'ОПИСАНИЕ',
    'items' => [
      [
        'title' => 'ДИАЛОГОВАЯ ЗАЩИТА',
        'description' => 'Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'ЗАЩИТА ОТ РАДИОПОМЕХ',
        'description' => 'StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'SUPER SLAVE (ОПЦИЯ)',
        'description' => 'Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ',
        'description' => 'StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'КОНТРОЛЬ КАНАЛА СВЯЗИ',
        'description' => 'Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)',
        'description' => 'Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля. <br> Опция доступна при интеграции 2CAN+2LIN интерфейса.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => '3D ДАТЧИК УДАРА И НАКЛОНА',
        'description' => 'Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)',
        'description' => 'iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ]
    ],
    'items-service' => [
      [
        'title' => 'ТЕЛЕМАТИКА (ОПЦИЯ)',
        'description' => 'Интеграция опциональных <a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1" target="_blank">GSM</a>,<a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/" target="_blank">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)',
        'description' => 'Интеграция опционального <a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/" target="_blank"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)',
        'description' => 'С помощью простого и удобного мониторинга <a href="https://www.starline-online.ru/" target="_blank">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/" target="_blank">GPS-ГЛОНАСС</a> и наличии опционального <a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/" target="_blank"> GSM-ИНТЕРФЕЙСА</a>.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'АВТОЗАПУСК',
        'description' => 'Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => '2CAN+2LIN (ОПЦИЯ)',
        'description' => 'Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ',
        'description' => 'Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'УДАРОПРОЧНЫЙ БРЕЛОК',
        'description' => 'Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)',
        'description' => 'StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href="https://can.starline.ru/" target="_blank">CAN.STARLINE.RU</i>',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
    ]
  ],
  [
    "id" => "product_keychain_e96-eco",
    'title' => 'ХАРАКТЕРИСТИКИ',
    'items' => [
      [
        'title' => 'РРРАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ]
    ]
  ],
  [
    "id" => "product_keychain_starline_a931",
    'title' => 'ГАРАНТИЯ',
    'items' => [
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ]
    ]
  ],
  [
    "id" => "product_keychain_starline_e961",
    'title' => 'СООТВЕТСТВУЮЩИЕ ТОВАРЫ',
    'items' => [
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
        'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ]
    ]
  ],
]; */

$tabs = [
  [
    'id' => 'product_keychain_a93-eco',
    'tabs' => [
      'ОПИСАНИЕ' => [
        'items' => [
          [
            'title' => 'ДИАЛОГОВАЯ ЗАЩИТА',
            'description' => 'Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'ЗАЩИТА ОТ РАДИОПОМЕХ',
            'description' => 'StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'SUPER SLAVE (ОПЦИЯ)',
            'description' => 'Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР',
            'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ',
            'description' => 'StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'КОНТРОЛЬ КАНАЛА СВЯЗИ',
            'description' => 'Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)',
            'description' => 'Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля. <br> Опция доступна при интеграции 2CAN+2LIN интерфейса.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => '3D ДАТЧИК УДАРА И НАКЛОНА',
            'description' => 'Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)',
            'description' => 'iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
        ],
        'items-service' => [
          [
            'title' => 'ТЕЛЕМАТИКА (ОПЦИЯ)',
            'description' => 'Интеграция опциональных <a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1" target="_blank">GSM</a>,<a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/" target="_blank">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)',
            'description' => 'Интеграция опционального <a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/" target="_blank"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)',
            'description' => 'С помощью простого и удобного мониторинга <a href="https://www.starline-online.ru/" target="_blank">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/" target="_blank">GPS-ГЛОНАСС</a> и наличии опционального <a href="https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/" target="_blank"> GSM-ИНТЕРФЕЙСА</a>.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'АВТОЗАПУСК',
            'description' => 'Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => '2CAN+2LIN (ОПЦИЯ)',
            'description' => 'Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ',
            'description' => 'Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'УДАРОПРОЧНЫЙ БРЕЛОК',
            'description' => 'Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
          [
            'title' => 'УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)',
            'description' => 'StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href="https://can.starline.ru/" target="_blank">CAN.STARLINE.RU</i>',
            'path-icon' => "$path/assets/images/vectors/thermometer.svg",
          ],
        ],
      ],
      'ХАРАКТЕРИСТИКИ' => [
        [
          'title' => 'Функции',
          'description' => 'Автозапуск, Управление предпусковым подогревом',
        ],
        [
          'title' => 'Категория',
          'description' => 'Для легкового авто, Для внедорожника',
        ],
      ],
      'ГАРАНТИЯ' => [
        [
          'description' => 'StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим',
        ],
      ],
      'СООТВЕТСТВУЮЩИЕ ТОВАРЫ' => [
        [
          'description' => 'Товар 1',
        ],
        [
          'description' => 'Товар 2',
        ],
      ],
    ],
  ],
  [
    'id' => 'product_keychain_e96-eco',
    'tabs' => [
      'ОПИСАНИЕ' => [
        [
          'title' => 'НОВАЯ ФУНКЦИЯ',
          'description' => 'Описание новой функции для другого товара.',
          'path-icon' => "$path/assets/images/vectors/new-feature.svg",
        ],
      ],
      'ХАРАКТЕРИСТИКИ' => [
        [
          'title' => 'Функции',
          'description' => 'Новый функционал',
        ],
      ],
      'ГАРАНТИЯ' => [
        [
          'description' => 'Гарантия для другого товара.',
        ],
      ],
      'СООТВЕТСТВУЮЩИЕ ТОВАРЫ' => [
        [
          'description' => 'Товар 1',
        ],
        [
          'description' => 'Товар 2',
        ],
      ],
    ],
  ],
];


?>