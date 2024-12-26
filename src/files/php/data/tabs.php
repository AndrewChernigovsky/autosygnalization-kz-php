<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$tabs = [
  [
    'title' => 'ОПИСАНИЕ',
    'items' => [
      [
        'title' => 'title 1',
        'description' => 'description 1',
        'path-icon' => 'icon-path-1',
      ],
      [
        'title' => 'title 2',
        'description' => 'description 2',
        'path-icon' => 'icon-path-2',
      ]
    ]
  ],
  [
    'title' => 'ХАРАКТЕРИСТИКИ',
    'items' => [
      [
        'title' => 'title 3',
        'description' => 'description 3',
        'path-icon' => 'icon-path-3',
      ],
      [
        'title' => 'title 4',
        'description' => 'description 4',
        'path-icon' => 'icon-path-4',
      ]
    ]
  ],
  [
    'title' => 'ГАРАНТИЯ',
    'items' => [
      [
        'title' => 'title 5',
        'description' => 'description 5',
        'path-icon' => 'icon-path-5',
      ],
      [
        'title' => 'title 6',
        'description' => 'description 6',
        'path-icon' => 'icon-path-6',
      ]
    ]
  ],
  [
    'title' => 'СООТВЕТСТВУЮЩИЕ ТОВАРЫ',
    'items' => [
      [
        'title' => 'title 7',
        'description' => 'description 7',
        'path-icon' => 'icon-path-7',
      ],
      [
        'title' => 'title 8',
        'description' => 'description 8',
        'path-icon' => 'icon-path-8',
      ]
    ]
  ],
];

?>