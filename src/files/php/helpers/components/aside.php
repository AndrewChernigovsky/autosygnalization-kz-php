<?php

class Aside
{

  public function createComponent($title, $src, $href, $quantity)
  {
    ob_start();
    ?>

    <article class="aside">
      <h4><?= $title ?></h4>
      <img src="<?= $src ?>" alt="" width="130" heigth="130">

      <div class="menu-btns">
        <p><span><?= $quantity ?></span> товаров</p>
        <a href="<?= $href ?>">в раздел</a>
      </div>
    </article>

    <?php
    return ob_get_clean();

  }
}
?>