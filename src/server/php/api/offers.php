<?php

namespace API;

use HELPERS\SetVariables;

$variables = new SetVariables();
$variables->setVar();
$path = $variables->getPathFileURL();

error_log($path . ' PATH');

$data = [
  [
    "title" => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОВ',
    "image" => "$path/client/images/services/service-9.avif",
    "href" => "$path/server/php/pages/service/service.php?service=setup",
    "quantity" => 11,
    "alt" => "Описание"
  ],
  [
    "title" => 'КАТАЛОГ АВТОСИГНАЛИЗАЦИЙ STARLINE',
    "image" => "$path/client/images/services/service-7.avif",
    "href" => "$path/server/php/pages/autosygnals/autosygnals.php?auto=catalog",
    "quantity" => 12,
    "alt" => "Описание"
  ],
];

header('Content-Type: application/json');
echo json_encode($data);
