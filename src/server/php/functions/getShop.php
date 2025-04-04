<?php

namespace FUNCTIONS;

use DATA\SetupsData;

function getShop($type = 'shop')
{

    $setups = (new SetupsData())->getData();
    $shop = $setups[$type];
    ob_start();

    ?>
  <section class='setup'>
    <div class='container'>
      <div class='setup__container'>
        <div class='setup__wrapper'>
          <h2 class='setup__title'><?= htmlspecialchars($shop['title']); ?></h2>
          <?php foreach ($shop['descs'] as $desc): ?>
            <p class='setup__description'><?= htmlspecialchars($desc); ?></p>
          <?php endforeach; ?>
          <a href="<?= htmlspecialchars($shop['url']); ?>"
            class='setup__btn button'><?= htmlspecialchars($shop['link-text']); ?></a>
        </div>
        <div class='setup__img-container'>
          <picture>
            <source type='image/png' media='(min-width: 1030px)'
              srcset="<?= htmlspecialchars($shop['src']['desktop']); ?>" width='700' height='554'>
            <img src="<?= htmlspecialchars($shop['src']['mob']); ?>" width='300' height='350' alt='Сервис'>
          </picture>
        </div>
      </div>
    </div>
  </section>
  <?php
    return ob_get_clean();
}
?>