<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$icons = [
  [
    "path" => "$path/assets/images/vectors/facebook-icon.svg",
    "name"=> "facebook",
    "width" => 20,
    "height" => 20,
    "href" => "#"
  ],
  [
    "path" => "$path/assets/images/vectors/vk-icon.svg",
    "name" => "vk",
    "width" => 20,
    "height" => 20,
    "href" => "#"
  ],
  [
    "path" => "$path/assets/images/vectors/ok-icon.svg",
    "name" => "ok",
    "width" => 20,
    "height" => 20,
    "href" => "#"
  ],
  [
    "path" => "$path/assets/images/vectors/google-icon.svg",
    "name" => "google",
    "width" => 20,
    "height" => 20,
    "href" => "#"
  ],  
]

?>