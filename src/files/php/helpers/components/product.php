<?php

function getProductCard($products, $id)
{
  if (!is_array($products)) {
    return '';
  }

  ob_start();

  foreach ($products['category'] as $category) {
    foreach ($category as $product) {
      if ($product['id'] === $id) {
        ?>
        <article class='product-card'>
          <img class="product-card__image" src="<?php echo htmlspecialchars($product['gallery'][0]); ?>"
            alt="<?php echo htmlspecialchars($product['description']); ?>" width="300" height="250">
          <h3 class="product-card__title"><?php echo htmlspecialchars($product['title']); ?></h3>
          <?php if (isset($product['description'])): ?>
            <p class="product-card__description"><?php echo htmlspecialchars($product['description']); ?></p>
          <?php endif; ?>
          <?php if (isset($product['price'])): ?>
            <p class="product-card__price"><span>Цена: </span><?php echo htmlspecialchars($product['price']); ?>
              <?php echo htmlspecialchars($product['currency']); ?>
            </p>
          <?php endif; ?>
        </article>
        <?php
      }
    }
  }
  return ob_get_clean();
}
function getProductCardWModel(array $products, bool $cart = false)
{
  if (!is_array($products)) {
    return '';
  }
  $groupedProducts = [];
  foreach ($products['category'] as $category) {
    foreach ($category as $product) {
      if (isset($product['id'])) {
        if (!isset($groupedProducts[$product['id']])) {
          $groupedProducts[$product['id']] = $product;
          $groupedProducts[$product['id']]['quantity'] = 1;
        } else {
          $groupedProducts[$product['id']]['quantity'] += 1;
        }
      }
    }
  }

  ob_start();

  foreach ($groupedProducts as $product) {
    ?>
    <article class='product-card' id="<?php echo htmlspecialchars($product['id']); ?>">
      <div class="product-card__bg">
        <img src="<?php echo htmlspecialchars($product['gallery'][0]); ?>"
          alt="<?php echo htmlspecialchars($product['description']); ?>" width="300" height="250">
      </div>
      <div class="product-card__body">
        <div class="product-card__head">
          <h3><?php echo htmlspecialchars($product['title']); ?></h3>
          <?php if (isset($product['price'])): ?>
            <p><span>Цена:
              </span><?php echo htmlspecialchars($product['price']); ?><?php echo htmlspecialchars($product['currency']); ?>
            </p>
          <?php endif; ?>
        </div>
        <?php if (!$cart): ?>
          <div class="product-card__buttons">
            <a class="button y-button-secondary" href="<?php echo htmlspecialchars($product['link']); ?>">Подробнее</a>
            <button type="button" class="button y-button-primary cart-button"
              data-id="<?php echo htmlspecialchars($product['id']); ?>" data-cost="<?= $product['price'] ?>">Купить</button>
          </div>
        <?php endif; ?>
        <?php if (isset($product['quantity']) && $cart): ?>
          <p>Количество: <?php echo htmlspecialchars($product['quantity']); ?></p>
        <?php endif; ?>
      </div>
    </article>
    <?php
  }
  return ob_get_clean();
}
function getProductCardMore($products, $id)
{
  if (!is_array($products)) {
    return '';
  }

  ob_start();

  foreach ($products['category'] as $category) {
    foreach ($category as $product) {
      if ($product['id'] === $id) {
        ?>
          <article class='product-card'>
            <div class="slider" id="swiper-article">
              <div class="swiper-container big-slider">
                <div class="swiper-wrapper">
                  <?php if (!empty($product['gallery'][0])): ?>
                  <div class="swiper-slide big-slide">
                    <img class="product-card__image" src="<?php echo htmlspecialchars($product['gallery'][0]); ?>"
                      alt="<?php echo htmlspecialchars($product['description']); ?>" width="300" height="250">
                  </div>
                  <?php endif; ?>
                </div>
              </div>            
              <div class="swiper-container thumbs">
                <div class="swiper-wrapper">
                  <?php for ($i = 1; $i < count($product['gallery']); $i++): ?>
                    <?php if (isset($product['gallery'][$i])): ?>
                      <div class="swiper-slide small-slide">
                        <img src="<?php echo htmlspecialchars($product['gallery'][$i]); ?>"
                          alt="<?php echo htmlspecialchars($product['description']); ?>" width="100" height="80">
                      </div>
                    <?php endif; ?>
                  <?php endfor; ?>
                </div>
              </div>
            </div>
            <div>
              <h3 class="product-card__title"><?php echo htmlspecialchars($product['title']); ?></h3>
              <?php if (isset($product['description'])): ?>
              <p class="product-card__description"><?php echo htmlspecialchars($product['description']); ?></p>
              <?php endif; ?>
              <?php if (isset($product['price'])): ?>
              <p class="product-card__price"><span>Цена: </span><?php echo htmlspecialchars($product['price']); ?>
                <?php echo htmlspecialchars($product['currency']); ?>
              </p>
              <?php endif; ?>
            </div>
          </article>
        <?php
        return ob_get_clean();
      }
    }
  }
  return '';
}
?>