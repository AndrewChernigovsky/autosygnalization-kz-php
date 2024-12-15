<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$setups = [
  "shop" => [
    "title" => 'Магазин',
    "descs" => [
      'Наилучшие предложения автоэлектроники в нашем магазине! Выбирайте, что Вас интересует!',
    ],
    "src" => [
      "mob" => "$path/assets/images/setup/shop-img.png",
      "desktop" => "$path/assets/images/setup/shop-img.png",
    ],
    "url"=> "#",
    "link-text" => "В магазин"
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
    ],
    "url" => "#",
    "link-text" => "В раздел"
  ],
];
?>
