<?php
include __DIR__ . '/../data/products.php';
include_once __DIR__ . '/../helpers/components/popular-card.php';
use HELPERS\SetVariables;

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();
$card = new PopularCard();
$path_href = "/catalog?SELECT=name&PAGE=1";
?>
<section class="popular" id="popular">
  <div class="container">
    <h2 class="secondary-title popular__title">Популярные товары</h2>
    <?php if (!empty($products)): ?>
      <div class="swiper swiper-popular">
        <ul class="popular__list swiper-wrapper list-style-none">
          <?php
          foreach ($products['category'] as $category):
              foreach ($category as $product):
                  if ($product['popular'] === true): ?>
                <li class="popular__item swiper-slide">
                  <div class="popular__item-bg">
                    <div class="swiper swiper-popular-gallery">
                      <div class="swiper-wrapper">
                        <?php foreach ($product['gallery'] as $image): ?>
                          <?= $card->getCard($image); ?>
                        <?php endforeach; ?>
                      </div>
                      <div class="swiper-button-prev"></div>
                      <div class="swiper-button-next"></div>
                    </div>
                    <h3 class="popular__item-title"><?= htmlspecialchars($product['title']); ?></h3>
                    <p class="popular__item-block">
                      <span class="popular__item-price"><?= htmlspecialchars($product['price']); ?></span>
                      <span class="popular__item-currency"><?= htmlspecialchars($product['currency']); ?></span>
                    </p>
                  </div>
                  <div class="popular__item-buttons">
                    <a class="popular__item-link link y-button-secondary"
                      href="<?= htmlspecialchars($product['link']); ?>" >Подробнее</a>
                    <a class="popular__item-link link y-button-primary  cart-button"
                      href="<?= "/cart";?>" data-cost="<?= htmlspecialchars($product['price'])?>" data-id="<?= htmlspecialchars($product['id'])?>">Купить</a>
                  </div>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </ul>
        <div class="swiper-pagination"></div>
        <a href="<?= htmlspecialchars($path_href) ?>" class="link button y-button-primary popular__all-products">Все товары</a>
      </div>
    <?php else: ?>
      <p style="color: black; margin: 0 auto; text-align: center;">Нет доступных популярных товаров.</p>
    <?php endif; ?>

  </div>
</section>
