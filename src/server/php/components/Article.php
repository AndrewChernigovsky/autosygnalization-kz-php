<?php

namespace COMPONENTS;

class Article
{
  public function createComponent($data)
  {
    ob_start();
    ?>


    <article class="article">
      <a href="<?= $data['href'] ?>">
        <h4 class="article__title"><?= $data['title'] ?></h4>
        <img class="article__image" src="<?= $data['image'] ?>" alt="<?= $data['alt'] ?>" width="130" heigth="130">


        <div class="article__menu-btns">
          <p class="article__quantity y-button-secondary"><span><?= $data['quantity'] ?></span> товаров</p>
          <p class="article__link y-button-primary link">в раздел</p>
        </div>
      </a>
    </article>

    <?php
    return ob_get_clean();

  }
}
?>