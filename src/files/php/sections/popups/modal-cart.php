<?php include_once __DIR__ . '/../../helpers/classes/setVariables.php';
$variables = new SetVariables();
$variables->
  setVar();
$path = $variables->getPathFileURL();
?>

<template id="cart-popup">
  <div class="cart-popup">
    <button type="button" id="close-cart-popup">
      <span class="visually-hidden">Закрыть модальное окно</span>
    </button>
    <p class="cart-popup__count"></p>
    <p class="cart-popup__summary"></p>
    <a class="cart-popup__link link button y-button-primary"
      href="<?= $path . '/files/php/pages/cart/cart.php'; ?>">Перейти в
      корзину</a>
    <p class="cart-popup__timer"></p>
  </div>
</template>