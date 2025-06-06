<?php

namespace COMPONENTS;

class ModalCart
{
    public function render(): string
    {
        return <<<HTML
<template id="cart-popup">
  <div class="cart-popup">
    <button type="button" id="close-cart-popup">
      <span class="visually-hidden">Закрыть модальное окно</span>
    </button>
    <p class="cart-popup__count"></p>
    <p class="cart-popup__all-count"></p>
    <p class="cart-popup__summary"></p>
    <a class="cart-popup__link link button y-button-primary"
      href="/cart">Перейти в
      корзину</a>
    <p class="cart-popup__timer"></p>
  </div>
</template>
HTML;
    }
}
