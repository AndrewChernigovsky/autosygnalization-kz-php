import { CartButton } from "../helpers/classes/CartButton";
import { ProductAPI } from "./api/getProduct.js";

const cartButtons = document.querySelectorAll('.cart-button');
const cartCounter = document.querySelector('.cart .counter');

export function cartButtonHandler() {
  if (cartButtons.length > 0) {
    const productApi = new ProductAPI();
    cartButtons.forEach(btn => btn.addEventListener('click', () => {
      console.log(btn.dataset.id, 'DATAID');
      const initBnt = new CartButton();
      // initBnt.updateProductQuantity(btn.dataset.id)
      // productApi.getProduct(btn.dataset.id);
      // productApi.createProducts();
      productApi.addProduct(2);
      productApi.getQuantity().then(data => {
        cartCounter.textContent = data; // Обновляем текст счетчика корзины
      }).catch(err => {
        console.error('Ошибка при получении количества товаров: ', err);
      });
      // productApi.getProductAll();
      // productApi.getProductByCategory('keychain');
      // productApi.addProduct(btn.dataset.id);
      // initBnt.updateCart();
    }))
  }
}