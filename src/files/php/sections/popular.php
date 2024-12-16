<?php
include_once __DIR__ . '/../data/products.php';
include_once __DIR__ . '/../helpers/components/popular-card.php';

$card = new PopularCard();
?>
<section class="popular" id="popular">
  <div class="container">
    <h2 class="secondary-title popular__title">Популярные товары</h2>

    <div class=" swiper swiper-popular">
      <ul class="popular__list swiper-wrapper list-style-none">
        <?php foreach ($products as $product): ?>
          <li class="popular__item swiper-slide">
            <h3><?= $product['title'] ?></h3>
            <div class="swiper swiper-popular-gallery">
              <div class="swiper-wrapper">
                <?php foreach ($product['gallery'] as $image): ?>
                  <?= $card->getCard($image); ?>
                <?php endforeach; ?>
              </div>
            </div>
            <p>
              <span><?= $product['price'] . ' '; ?></span>
              <span><?= $product['currency']; ?></span>
            </p>
            <div class="popular__item-buttons">
              <a href="<?= htmlspecialchars($product['link']); ?>">Подробнее</a>
              <a href="<?= htmlspecialchars($product['link']); ?>">Купить</a>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>