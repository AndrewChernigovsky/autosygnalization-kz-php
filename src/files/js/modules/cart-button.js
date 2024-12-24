import { CartButton } from "../helpers/classes/CartButton";
import { ProductAPI } from "./api/getProduct.js";

const cartButtons = document.querySelectorAll('.cart-button');

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
      // productApi.getProductAll();
      // productApi.getProductByCategory('keychain');
      // productApi.addProduct(btn.dataset.id);
      // initBnt.updateCart();
    }))
  }
}