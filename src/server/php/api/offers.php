<?php

namespace API;

$data = [
  [
    "title" => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ',
    "image" => "/client/images/services/service-9.avif",
    "href" => "/service?service=setup",
    "quantity" => 11,
    "alt" => "Описание"
  ],
  [
    "title" => 'КАТАЛОГ АВТОСИГНАЛИЗАЦИЙ STARLINE',
    "image" => "/client/images/services/service-7.avif",
    "href" => "/autosygnals?auto=catalog",
    "quantity" => 12,
    "alt" => "Описание"
  ],
];

header('Content-Type: application/json');
echo json_encode($data);
