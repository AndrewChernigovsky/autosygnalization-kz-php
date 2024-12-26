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
        'title' => 'title 1',
        'description' => 'description 1',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'title 2',
        'description' => 'description 2',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ]
    ]
  ],
  [
    "id" => generateID(),
    'title' => 'ХАРАКТЕРИСТИКИ',
    'items' => [
      [
        'title' => 'title 3',
        'description' => 'description 3',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'title 4',
        'description' => 'description 4',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ]
    ]
  ],
  [
    "id" => generateID(),
    'title' => 'ГАРАНТИЯ',
    'items' => [
      [
        'title' => 'title 5',
        'description' => 'description 5',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'title 6',
        'description' => 'description 6',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ]
    ]
  ],
  [
    "id" => generateID(),
    'title' => 'СООТВЕТСТВУЮЩИЕ ТОВАРЫ',
    'items' => [
      [
        'title' => 'title 7',
        'description' => 'description 7',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ],
      [
        'title' => 'title 8',
        'description' => 'description 8',
        'path-icon' => "$path/assets/images/vectors/thermometer.svg",
      ]
    ]
  ],
];

?>