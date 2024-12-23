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
function getProductCardWModel($products)
{
  if (!is_array($products)) {
    return '';
  }

  ob_start();

  foreach ($products as $product) {
    ?>
    <article class='product-card' id="<?php echo $product['id']; ?>">
      <div class="product-card__bg">
        <img src="<?php echo $product['gallery'][0]; ?>" alt="<?php echo $product['description'] ?>" width="300"
          heigth="250">
      </div>
      <div class="product-card__body">
        <h3><?php echo htmlspecialchars($product['title']); ?></h3>
        <?php if (isset($product['price'])): ?>
          <p>Цена: <?php echo htmlspecialchars($product['price']); ?><?php echo htmlspecialchars($product['currency']); ?></p>
        <?php endif; ?>
        <a class="button y-button-secondary" href="<?php echo $product['link'] ?>">Подробнее</a>
        <button type="button" class="button y-button-primary cart-button"
          data-id="<?php echo $product['id'] ?>">Купить</button>
      </div>
    </article>
    <?php
  }
  return ob_get_clean();
}
?>