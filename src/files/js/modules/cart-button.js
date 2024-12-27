import { ProductAPI } from "./api/getProduct.js";

const cartButtons = document.querySelectorAll('.cart-button');
const cartCounter = document.querySelector('.cart .counter');

export function cartButtonHandler() {
  let products = JSON.parse(localStorage.getItem('cart')) || [];

  if (cartCounter) {
    const currentCount = products.reduce((total, product) => total + product.quantity, 0);
    cartCounter.textContent = currentCount;

    if (cartButtons.length > 0) {
      const productApi = new ProductAPI();
      productApi.createProducts();


      cartButtons.forEach(btn => btn.addEventListener('click', () => {
        const productId = btn.dataset.id;
        const existingProduct = products.find(product => product.id === productId);

        if (existingProduct) {
          existingProduct.quantity += 1;
        } else {
          products.push({ id: productId, quantity: 1 });
        }

        localStorage.setItem('cart', JSON.stringify(products));
        productApi.addProduct(productId);

        const newCount = products.reduce((total, product) => total + product.quantity, 0);
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
