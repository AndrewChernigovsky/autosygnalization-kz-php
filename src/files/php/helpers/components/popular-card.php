<?php

class PopularCard
{

  public function getCard($image)
  {
    $output = "";

    if (isset($image)) {
      $output .= "<div class='swiper-slide swiper-popular-gallery__slide'>";
      $output .= "<img class='swiper-popular-gallery__image' src=" . htmlspecialchars($image) . " alt='Auto Security. Галлерея популярных товаров.'>";
      $output .= "</div>";

    }

    return $output;
  }

}

?>