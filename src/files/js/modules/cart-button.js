import { ProductAPI } from "./api/getProduct.js";

const cartButtons = document.querySelectorAll('.cart-button');
const cartCounter = document.querySelector('.cart .counter');

export function cartButtonHandler() {
  if (cartButtons.length > 0) {
    const productApi = new ProductAPI();
    cartButtons.forEach(btn => btn.addEventListener('click', () => {
      productApi.addProduct(btn.dataset.id);
      let currentCount = localStorage.getItem('count') ? JSON.parse(localStorage.getItem('count')) : {};
      if (currentCount['count']) {
        currentCount['count'] += 1;
      } else {
        currentCount['count'] = 1;
      }

      localStorage.setItem('count', JSON.stringify(currentCount));
      cartCounter.textContent = currentCount['count'];
    }))
  }
}