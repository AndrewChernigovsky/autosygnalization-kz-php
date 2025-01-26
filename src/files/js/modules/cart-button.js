import { ProductAPI } from "./api/getProduct.js";

const cartButtons = document.querySelectorAll('.cart-button');
const cartCounter = document.querySelector('.cart .counter');

export function cartButtonHandler() {
  let products = JSON.parse(sessionStorage.getItem('cart')) || [];

  if (cartCounter) {

    const currentCount = products.reduce((total, product) => total + product.quantity, 0);
    cartCounter.textContent = currentCount;

    if (cartButtons.length > 0) {
      const productApi = new ProductAPI();

      cartButtons.forEach(btn => btn.addEventListener('click', (e) => {
        const productId = btn.dataset.id;
        const productPrice = btn.dataset.cost;
        const existingProduct = products.find(product => product.id === productId);

        if (existingProduct) {
          existingProduct.quantity += 1;
        } else {
          products.push({ id: productId, quantity: 1, price: productPrice });
        }

        sessionStorage.setItem('cart', JSON.stringify(products));

        const newCount = products.reduce((total, product) => {
          return total + product.quantity;
        }, 0);

        cartCounter.textContent = newCount;

        productApi.sendCart(products).then(responseData => {
          console.log('Данные успешно отправлены:', responseData);
        }).catch(error => {
          console.error('Ошибка при отправке данных:', error);
        });
      }));
    }
  }
}
