<?php

namespace SECTIONS;

use DATA\Products;
use COMPONENTS\PopularCard;

function popularSection(): string
{
  // Инициализация данных
  $products = (new Products())->getData();
  $card = new PopularCard();
  $pathHref = "/catalog";

  // Если товаров нет, возвращаем сообщение об отсутствии товаров
  if (empty($products)) {
    return <<<HTML
<section class="popular" id="popular">
  <div class="container">
    <h2 class="secondary-title popular__title">Популярные товары</h2>
    <p style="color: black; margin: 0 auto; text-align: center;">Нет доступных популярных товаров.</p>
  </div>
</section>
HTML;
  }

  // Генерация HTML для популярных товаров
  ob_start();
  ?>
  <section class="popular" id="popular">
    <div class="container">
      <h2 class="secondary-title popular__title">Популярные товары</h2>
      <div class="swiper swiper-popular">
        <ul class="popular__list swiper-wrapper list-style-none">
          <?php
          foreach ($products as $product):
            if (!empty($product['is_popular']) && $product['is_popular'] === true): ?>
              <li class="popular__item swiper-slide">
                <a href="<?= htmlspecialchars($product['link'] ?? ''); ?>" class="popular__item-bg">
                  <div class="swiper swiper-popular-gallery">
                    <div class="swiper-wrapper">
                      <?php foreach ($product['gallery'] as $image): ?>
                        <?= $card->getCard($image); ?>
                      <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                  </div>
                  <h3 class="popular__item-title"><?= htmlspecialchars($product['title'] ?? ''); ?></h3>
                  <p class="popular__item-block">
                    <span class="popular__item-price"><?= htmlspecialchars($product['price'] ?? ''); ?></span>
                    <span class="popular__item-currency"><?= htmlspecialchars($product['currency'] ?? ''); ?></span>
                  </p>
                </a>
                <div class="popular__item-buttons">
                  <a class="popular__item-link link y-button-secondary"
                    href="<?= htmlspecialchars($product['link'] ?? ''); ?>">Подробнее</a>
                  <a class="popular__item-link link y-button-primary cart-button" href="/cart"
                    data-cost="<?= htmlspecialchars($product['price'] ?? ''); ?>"
                    data-id="<?= htmlspecialchars($product['id'] ?? ''); ?>">Купить</a>
                </div>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
        <div class="swiper-pagination"></div>
        <a href="<?= htmlspecialchars($pathHref); ?>" class="link button y-button-primary popular__all-products">Все
          товары</a>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
