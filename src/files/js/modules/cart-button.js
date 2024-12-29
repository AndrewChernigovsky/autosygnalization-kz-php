import { ProductAPI } from "./api/getProduct.js";
// import Popup from "../helpers/classes/Popup.js";

const cartButtons = document.querySelectorAll('.cart-button');
const cartCounter = document.querySelector('.cart .counter');

export function cartButtonHandler() {
  let products = JSON.parse(sessionStorage.getItem('cart')) || [];
  // const setPopup = new Popup();

  if (cartCounter) {

    const currentCount = products.reduce((total, product) => total + product.quantity, 0);
    cartCounter.textContent = currentCount;

    if (cartButtons.length > 0) {
      const productApi = new ProductAPI();
      productApi.createProducts();


      cartButtons.forEach(btn => btn.addEventListener('click', (e) => {
        const productId = btn.dataset.id;
        const existingProduct = products.find(product => product.id === productId);

        if (existingProduct) {
          existingProduct.quantity += 1;
        } else {
          products.push({ id: productId, quantity: 1 });
        }

        console.log(131231);
        sessionStorage.setItem('cart', JSON.stringify(products));
        productApi.addProduct(productId);

        const newCount = products.reduce((total, product) => {
          // if (e.currentTarget.dataset.id === product.id) {
          //   setPopup.initPopup(e.currentTarget, productId, product.quantity);
          // }
          return total + product.quantity;
        }, 0);
        cartCounter.textContent = newCount;

        console.log(products, 'PRODUTCS');
        productApi.sendCart(products).then(responseData => {
          console.log('Данные успешно отправлены:', responseData);
        }).catch(error => {
          console.error('Ошибка при отправке данных:', error);
        });
      }));
    }
  }

}
