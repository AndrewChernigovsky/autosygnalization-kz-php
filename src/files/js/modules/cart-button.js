import { ProductAPI } from "./api/getProduct.js";

const cartButtons = document.querySelectorAll('.cart-button');
const cartCounter = document.querySelector('.cart .counter');

export function cartButtonHandler() {
  if (cartButtons.length > 0) {
    const productApi = new ProductAPI();
    cartButtons.forEach(btn => btn.addEventListener('click', () => {
      const productId = btn.dataset.id;
      productApi.addProduct(productId);

      let currentCount = localStorage.getItem('count') ? JSON.parse(localStorage.getItem('count')) : {};

      if (currentCount[productId]) {
        currentCount[productId] += 1; // Увеличиваем количество
      } else {
        currentCount[productId] = 1; // Устанавливаем количество в 1, если товара нет
      }


      localStorage.setItem('count', JSON.stringify(currentCount));
      cartCounter.textContent = currentCount['count'];
    }))
  }
}