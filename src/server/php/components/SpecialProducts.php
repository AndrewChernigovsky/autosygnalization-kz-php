<?php

namespace COMPONENTS;

use DATA\Products;

class SpecialProducts
{
    public function render()
    {
        $products = (new Products())->getData();

        ob_start(); // Начинаем буферизацию вывода
        ?>
      <section class="offers">
        <div class="swiper swiper-offers">
          <h3 class="offers__heading">Специальное предложение</h3>
          <ul class="offers__list list-style-none swiper-wrapper">
            <?php if (!empty($products['category'])): ?>  
              <?php foreach ($products['category']['keychain'] as $product): ?>
                <?php if ($product['special']): ?>
                  <li class="offers__item swiper-slide">
                    <img class="offers__image" src="<?php echo htmlspecialchars($product['gallery'][0]); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" height="300" width="200">
                    <h4 class="offers__title"><?= htmlspecialchars($product['title']); ?></h4>
                    <p class="offers__wrapper">
                      <span class="offers__price"><?= htmlspecialchars($product['price']); ?></span>
                      <span class="offers__currency"><?= htmlspecialchars($product['currency']); ?></span>
                    </p>
                    <a class="y-button-secondary button offers__link-more" href="<?= htmlspecialchars($product['link']); ?>">Подробнее</a>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
          <div class="offers__container">
            <a class="offers__link-all" href="<?= "special?SELECT=name&type=special&special=special" ?>">Все предложения</a>
            <div class="offers__buttons">
              <button class="offers__button offers__button--prev swiper-button-prev-offers " type="button"></button>
              <button class="offers__button offers__button--next swiper-button-next-offers " type="button"></button>
            </div>
          </div>
        </div>
      </section>
      <?php
        return ob_get_clean(); // Возвращаем HTML из буфера
    }
}

?>
