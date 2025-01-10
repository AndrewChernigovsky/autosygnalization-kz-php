<?php

class Article
{

  public function createComponent($data)
  {
    ob_start();
    ?>


    <article class="article">

      <h4 class="article__title"><?= $data['title'] ?></h4>
      <img class="article__image" src="<?= $data['image'] ?>" alt="<?= $data['alt'] ?>" width="130" heigth="130">


      <div class="article__menu-btns">
        <p class="article__quantity y-button-secondary"><span><?= $data['quantity'] ?></span> товаров</p>
        <a class="article__link y-button-primary link" href="<?= $data['href'] ?>">в раздел</a>
      </div>
    </article>

    <?php
    return ob_get_clean();

  }
}
?>