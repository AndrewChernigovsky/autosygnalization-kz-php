<?php

namespace FUNCTIONS;
function formatPriceWithSpaces($price)
{
  return number_format((int) $price, 0, '', ' ');
}
function getProductCard($products, $id)
{
  if (!is_array($products)) {
    return '';
  }

  ob_start();

  foreach ($products as $product) {
      if ($product['id'] === $id) {
        $price = formatPriceWithSpaces($product['price']);
        ?>
        <article class='product-card'>
          <img class="product-card__image" src="<?php echo htmlspecialchars($product['gallery'][0]); ?>"
            alt="<?php echo htmlspecialchars($product['description']); ?>" width="300" height="250">
          <h3 class="product-card__title"><?php echo htmlspecialchars($product['title']); ?></h3>
          <?php if (isset($product['description'])): ?>
            <p class="product-card__description"><?php echo htmlspecialchars($product['description']); ?></p>
          <?php endif; ?>
          <?php if (isset($product['price'])): ?>
            <p class="product-card__price"><span>Цена:
              </span><?php echo $price; ?>
              <?php echo htmlspecialchars($product['currency']); ?>
            </p>
          <?php endif; ?>
        </article>
        <?php
        break;
      }
  }
  return ob_get_clean();
}


function getProductCardWModel(array $products, bool $cart = false, $page = 1)
{
  error_log(print_r($products, true) . 'Я ОТРАБОТАЛ');
  if (!is_array($products)) {
    return '';
  }
  $groupedProducts = [];

  if (isset($products['category']) && is_array($products['category'])) {
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
  } else {
    foreach ($products as $product) {
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

  $groupedProducts = array_slice($groupedProducts, 0, $page * 10);
  if ($page > 1) {
    $groupedProducts = array_slice($groupedProducts, ($page - 1) * 10, $page * 10);
    error_log(print_r($groupedProducts, true) . ' GROUPED');
    error_log(print_r($page - 1 * 10, true) . ' PAGE OFFSET');
  }
  ob_start();

  foreach ($groupedProducts as $product) {
    $price = formatPriceWithSpaces($product['price']);
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
              </span><?php echo $price; ?>
              <span><?php echo htmlspecialchars($product['currency']); ?></span>
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
function getProductCardImage($products, $id)
{
  error_log(print_r($products, true) . 'produc2222222ts11s');
  if (!is_array($products)) {
    return '';
  }

  ob_start();

  foreach ($products as $product) {
      if ($product['id'] === $id) {
        ?>
        <article class='product-card'>
          <div class="slider" id="swiper-article">
            <div class="swiper big-slider">
              <div class="swiper-wrapper">
                <?php for ($i = 0; $i < count($product['gallery']); $i++): ?>
                  <?php if (isset($product['gallery'][$i])): ?>
                    <div class="swiper-slide big-slide">
                      <img src="<?php echo htmlspecialchars($product['gallery'][$i]); ?>"
                        alt="<?php echo htmlspecialchars($product['description']); ?>" width="300" height="250">
                    </div>
                  <?php endif; ?>
                <?php endfor; ?>
              </div>
            </div>
            <div class="swiper thumbs">
              <div class="swiper-wrapper">
                <?php for ($i = 0; $i < count($product['gallery']); $i++): ?>
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
        </article>
        <h3 class="product-card__title"><?php echo htmlspecialchars($product['title']); ?></h3>
        <?php
        return ob_get_clean();
      }
  }
  return '';
}

function getProductCardDescription($products, $id)
{

  error_log(print_r($products, true) . 'produ4444');
  if (!is_array($products)) {
    return '';
  }

  ob_start();

  foreach ($products as $product) {
      if ($product['id'] === $id) {
        $price = formatPriceWithSpaces($product['price']);
        ?>
        <div class='product-card__content'>
          <?php if (isset($product['description'])): ?>
            <p class="product-card__description"><?php echo htmlspecialchars($product['description']); ?></p>
          <?php endif; ?>
          <?php if (isset($product['price'])): ?>
            <p class="product-card__price" data-price="<?php echo htmlspecialchars($product['price']); ?>"><span>Цена: </span>
              <b>
                <?php echo $price; ?>
                <?php echo htmlspecialchars($product['currency']); ?>
              </b>
            </p>
          <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
      }
  }
  return '';
}

function newGetProductCardWModel(array $current_products_arr, bool $cart = false, $page = 1, $current_items_count_on_page = 5, callable $specialOffersCallback = null, $species = true)
{
  if (!is_array($current_products_arr)) {
    return '';
  }

  $groupedProducts = [];
  foreach ($current_products_arr as $item) {
    if (isset($item['id'])) {
      if (!isset($groupedProducts[$item['id']])) {
        $groupedProducts[$item['id']] = $item;
        $groupedProducts[$item['id']]['quantity'] = 1;
      } else {
        $groupedProducts[$item['id']]['quantity'] += 1;
      }
    }
  }

  $groupedProducts = array_slice($groupedProducts, ($page - 1) * $current_items_count_on_page, $current_items_count_on_page);

  ob_start();

  $productCount = 0;
  $totalProducts = count($groupedProducts);

  foreach ($groupedProducts as $product) {
    $productCount++;

    if ($specialOffersCallback !== null && ($productCount === $totalProducts)) {
      call_user_func($specialOffersCallback);
    }

    $price = formatPriceWithSpaces($product['price']);

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
            <p><span>Цена:</span> <?php echo $price; ?>
              <span><?php echo htmlspecialchars($product['currency']); ?></span>
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


?>