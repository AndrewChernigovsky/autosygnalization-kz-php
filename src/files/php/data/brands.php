<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$brands = [
  ["path" => "$path/assets/images/cars-brand/changan.avif",
   "name" => "changan"
],
  [
    "path" => "$path/assets/images/cars-brand/KIA.avif",
    "name" => "kia"
  ],
  [
    "path" => "$path/assets/images/cars-brand/lexus.avif",
    "name" => "lexus"
  ],
  [
    "path" => "$path/assets/images/cars-brand/toyota.avif",
    "name" => "toyota"
  ],
  [
    "path" => "$path/assets/images/cars-brand/chevrolet.avif",
    "name" => "chevrolet"
  ],
  [
    "path" => "$path/assets/images/cars-brand/haval.avif",
    "name" => "haval"
  ],
  [
    "path" => "$path/assets/images/cars-brand/hyundai.avif",
    "name" => "hyundai"
  ],
  [
    "path" => "$path/assets/images/cars-brand/mitsubishi.avif",
    "name" => "mitsubishi"
  ],
]
?>