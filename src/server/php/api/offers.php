<?php

namespace API;

$data = [
  [
    "title" => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОВ',
    "image" => "/client/images/services/service-9.avif",
    "href" => "/server/php/pages/service/service.php?service=setup",
    "quantity" => 11,
    "alt" => "Описание"
  ],
  [
    "title" => 'КАТАЛОГ АВТОСИГНАЛИЗАЦИЙ STARLINE',
    "image" => "/client/images/services/service-7.avif",
    "href" => "/server/php/pages/autosygnals/autosygnals.php?auto=catalog",
    "quantity" => 12,
    "alt" => "Описание"
  ],
];

header('Content-Type: application/json');
echo json_encode($data);
