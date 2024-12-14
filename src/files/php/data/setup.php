<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$setups = [
  "shop" => [
    "title" => 'Магазин',
    "descs" => [
      'Воспользуйтесь услугами нашего сервисного центра',
      'Прайс на оборудование и установку',
    ],
    "src" => [
      "mob" => "$path/assets/images/setup/setup-img-mobile.png",
      "desktop" => "$path/assets/images/setup/setup-img.png",
    ]
  ],
  "setup" => [
    "title" => 'Установочный центр',
    "descs" => [
      'Воспользуйтесь услугами нашего сервисного центра',
      'Прайс на оборудование и установку',
    ],
    "src" => [
      "mob" => "$path/assets/images/setup/setup-img-mobile.png",
      "desktop" => "$path/assets/images/setup/setup-img.png",
    ]
  ],
];
?>