<?php

namespace DATA;

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$SocialIconsData = [
  [
    'name' => 'facebook',
    'href' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($currentUrl),
    'path' => "/client/images/vectors/facebook-icon.svg",
    'width' => '20',
    'height' => '20',
    'attributes' => [
        'target' => '_blank',
        'rel' => 'noopener noreferrer',
        'data-social' => 'facebook'
    ]
],
  [
    'name' => 'VK',
    'href' => 'https://vk.com/share.php?url=' . urlencode($currentUrl),
    'path' => "/client/images/vectors/vk-icon.svg",
    'width' => '20',
    'height' => '20',
    'attributes' => [
        'target' => '_blank',
        'rel' => 'noopener noreferrer',
        'data-social' => 'vk'
    ]
],
[
  'name' => 'ok',
  'href' => 'https://connect.ok.ru/offer?url=' . urlencode($currentUrl),
  'path' => "/client/images/vectors/ok-icon.svg",
  'width' => '20',
  'height' => '20',
  'attributes' => [
      'target' => '_blank',
      'rel' => 'noopener noreferrer',
      'data-social' => 'ok'
  ]
],
[
  'name' => 'google',
  'href' => 'https://plus.google.com/share?url=' . urlencode($currentUrl),
  'path' => "/client/images/vectors/google-icon.svg",
  'width' => '20',
  'height' => '20',
  'attributes' => [
      'target' => '_blank',
      'rel' => 'noopener noreferrer',
      'data-social' => 'google'
  ]
  ],
];
