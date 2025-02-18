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
      "mob" => "$path/assets/images/setup/shop-img.avif",
      "desktop" => "$path/assets/images/setup/shop-img.avif",
    ],
    "url"=> "$path/files/php/pages/autosygnals/autosygnals.php",
    "link-text" => "В магазин"
  ],
  "setup" => [
    "title" => 'Установочный центр',
    "descs" => [
      'Воспользуйтесь услугами нашего сервисного центра',
    ],
    "src" => [
      "mob" => "$path/assets/images/setup/setup-img.avif",
      "desktop" => "$path/assets/images/setup/setup-img.avif",
    ],
    "url" => "$path/files/php/pages/service/service.php",
    "link-text" => "В раздел"
  ],
];
?>
