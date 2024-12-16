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

            <div class="swiper swiper-popular-gallery">
              <div class="swiper-wrapper">
                <?php foreach ($product['gallery'] as $image): ?>
                  <?= $card->getCard($image); ?>
                <?php endforeach; ?>
              </div>
            </div>
            <h3 class="popular__item-title"><?= $product['title'] ?></h3>
            <p class="popular__item-block">
              <span class="popular__item-price"><?= $product['price'] . ' '; ?></span>
              <span class="popular__item-currency"><?= $product['currency']; ?></span>
            </p>
            <div class="popular__item-buttons">
              <a class="popular__item-link link y-button-secondary"
                href="<?= htmlspecialchars($product['link']); ?>">Подробнее</a>
              <a class="popular__item-link link y-button-primary"
                href="<?= htmlspecialchars($product['link']); ?>">Купить</a>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>