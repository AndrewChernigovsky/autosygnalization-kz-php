<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

function generateID()
{
  return uniqid();
}

$tabs = [
  [
    "id" => generateID(),
    'title' => 'ОПИСАНИЕ',
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
      ]
    ]
  ],
  [
    "id" => generateID(),
    'title' => 'ХАРАКТЕРИСТИКИ',
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
      ]
    ]
  ],
  [
    "id" => generateID(),
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
    "id" => generateID(),
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
];

?>