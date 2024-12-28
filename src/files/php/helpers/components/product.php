<?php

function getProductCard($products, $model)
{
  if (!is_array($products)) {
    return '';
  }

  ob_start();

  foreach ($products as $product) {
    if ($product['model'] === $model) {
      ?>
      <article class='product-card'>
        <h3><?php echo htmlspecialchars($product['title']); ?></h3>
        <?php if (isset($product['description'])): ?>
          <p><?php echo htmlspecialchars($product['description']); ?></p>
        <?php endif; ?>
        <?php if (isset($product['price'])): ?>
          <p>Цена: <?php echo htmlspecialchars($product['price']); ?>         <?php echo htmlspecialchars($product['currency']); ?></p>
        <?php endif; ?>
      </article>
      <?php
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
  error_log(print_r($products['category'], true) . ' : PRODUCTSSS');
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
        <h3><?php echo htmlspecialchars($product['title']); ?></h3>
        <?php if (isset($product['price'])): ?>
          <p>Цена: <?php echo htmlspecialchars($product['price']); ?><?php echo htmlspecialchars($product['currency']); ?>
          </p>
        <?php endif; ?>
        <?php if (!$cart): ?>
          <a class="button y-button-secondary" href="<?php echo htmlspecialchars($product['link']); ?>">Подробнее</a>
          <button type="button" class="button y-button-primary cart-button"
            data-id="<?php echo htmlspecialchars($product['id']); ?>">Купить</button>
        <?php endif; ?>
        <?php if (isset($product['quantity'])): ?>
          <p>Количество: <?php echo htmlspecialchars($product['quantity']); ?></p>
        <?php endif; ?>
      </div>
    </article>
    <?php
  }
  return ob_get_clean();
}
?>