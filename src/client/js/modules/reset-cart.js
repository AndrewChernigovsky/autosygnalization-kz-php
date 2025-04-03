import { ProductAPI } from './api/getProduct.js';

export default class ResetCart {
  constructor(elem) {
    this.elem = elem;
    this.resetCart();
  }
  resetCart() {
    const initProduct = new ProductAPI();
    this.elem.addEventListener('click', () => {
      initProduct.clearCart();
      const counter = document.querySelector('.cart .counter');
      counter.textContent = 0;
      const productsWrapper = document.querySelector('.cart-section__products');
      productsWrapper.innerHTML = '';
      console.log('я почистил');
    });
  }
}
