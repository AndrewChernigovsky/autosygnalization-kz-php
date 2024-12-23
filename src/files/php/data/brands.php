<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$brands = [
  ["path" => "$path/assets/images/cars-brand/genesis.avif",
   "name" => "genesis"
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
    "path" => "$path/assets/images/cars-brand/vw.avif",
    "name" => "volkswagen"
  ],
  [
    "path" => "$path/assets/images/cars-brand/audi.avif",
    "name" => "audi"
  ],
  [
    "path" => "$path/assets/images/cars-brand/audi.avif",
    "name" => "audi"
  ],
]
?>