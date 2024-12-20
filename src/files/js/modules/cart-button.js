import { CartButton } from "../helpers/classes/CartButton";
const cartButtons = document.querySelectorAll('.cart-button');

export function cartButtonHandler() {
  if (cartButtons.length > 0) {
    cartButtons.forEach(btn => btn.addEventListener('click', () => {
      console.log(btn.dataset.id, 'DATAID');
      const initBnt = new CartButton(btn.dataset.id);
      initBnt.setCard('/dist/files/php/layout/header.php');
      // window.location.href = window.location.href + '?id=' + btn.dataset.id;
    }))
  }
}