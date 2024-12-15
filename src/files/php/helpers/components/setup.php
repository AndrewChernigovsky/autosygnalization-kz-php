<?php

function getShop($type = 'shop')
{
  include_once __DIR__ . '/../../data/setup.php';

  $shop = $setups[$type];
  $output = '';
  $output .= "
  <section class='setup'>
    <div class='container'>
        <div class='setup__container'>
          <div class='setup__wrapper'>
  ";
  $output .= "
          <h2 class='setup__title'> " .
    htmlspecialchars($shop['title'])
    . "</h2>
  ";

  foreach ($shop['descs'] as $desc) {
    $output .= "<p class='setup__description'>" . htmlspecialchars($desc) . "</p>";
  }

  $output .= "<a href=" . htmlspecialchars($shop['url']) . " . class='setup__btn button'>" . htmlspecialchars($shop['link-text']) . "</a>";
  $output .= "</div>";
  $output .= "<div class='setup__img-container'>";
  $output .= "<picture>";
  $output .= "<source type='image/png' media='(min-width: 1130px)' srcset=" . htmlspecialchars($shop['src']['desktop']) . " width='700' height='554'>";
  $output .= "<img src=" . htmlspecialchars($shop['src']['mob']) . " width='300' height='350' alt='Сервис'>";
  $output .= "</picture>";
  $output .= "</div>";
  $output .= "</div>";
  $output .= "</div>";
  $output .= "</section>";

  return $output;
}

?>