<?php

class Aside
{

  public function createComponent($data)
  {
    ob_start();
    ?>


    <article class="aside">

      <h4 class="aside__title"><?= $data['title'] ?></h4>
      <img class="aside__image" src="<?= $data['image'] ?>" alt="<?= $data['alt'] ?>" width="130" heigth="130">


      <div class="aside__menu-btns">
        <p class="aside__quantity y-button-secondary"><span><?= $data['quantity'] ?></span> товаров</p>
        <a class="aside__link y-button-primary link" href="<?= $data['href'] ?>">в раздел</a>
      </div>
    </article>

    <?php
    return ob_get_clean();

  }
}
?>