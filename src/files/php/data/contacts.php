<?php
$distPath = $_SERVER['DOCUMENT_ROOT'] . '/dist';

if (is_dir($distPath)) {
  $pathFile_URL = '/dist';
} else {
  $pathFile_URL = '';
}

$contacts_phone = [
  ['phone' => '+7 707 747 8212'],
  ['phone' => '+7 701 747 8212'],
];
$email = "autosecurity.kz@mail.ru";

$social = [
  ['name' => 'Instagram', 'width' => '50', 'height' => '50', "image" => $pathFile_URL . '/assets/images/vectors/sprite.svg#instagramm-icon', 'href' => '#'],
]
  ?>