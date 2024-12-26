import { ProductAPI } from "./api/getProduct.js";

const cartButtons = document.querySelectorAll('.cart-button');
const cartCounter = document.querySelector('.cart .counter');

export function cartButtonHandler() {
  if (cartButtons.length > 0) {
    const productApi = new ProductAPI();
    productApi.createProducts();

    let currentCount = localStorage.getItem('count') ? JSON.parse(localStorage.getItem('count')) : 0;
    cartCounter.textContent = currentCount;

    cartButtons.forEach(btn => btn.addEventListener('click', () => {
      const productId = String(btn.dataset.id);
      productApi.addProduct(productId);
      currentCount += 1;
      localStorage.setItem('count', JSON.stringify(currentCount));
      cartCounter.textContent = currentCount;
    }));
  }
}
