import FormatNumber from "../helpers/classes/FormatNumber";
export function setModalCart() {
  const template = document.getElementById('cart-popup');

  const cartData = sessionStorage.getItem('cart');
  const cartItems = cartData ? JSON.parse(cartData) : [];

  const clone = document.importNode(template.content, true);
  const countItem = clone.querySelector('.cart-popup__count');
  const timerElement = clone.querySelector('.cart-popup__timer');
  const cost = clone.querySelector('.cart-popup__summary');

  let allQuantity = 0;
  let allCost = 0;

  cartItems.forEach(item => {
    allQuantity += item.quantity;
    allCost += item.price * item.quantity;
  });
  const format = new FormatNumber();
  countItem.textContent = `Товаров в корзине: ${allQuantity}`;
  cost.textContent = `Сумма: ${format.customFormatNumber(allCost)} ₸`;


  let timer = 5;
  timerElement.textContent = `Скрытие через ${timer} секунд`;

  const interval = setInterval(() => {
    timer--;
    timerElement.textContent = `Скрытие через ${timer} секунд`;

    if (timer <= 0) {
      clearInterval(interval);
      // popup.remove();
    }
  }, 1000);

  const popup = document.createElement('div');
  popup.classList.add('cart-popup-container');
  popup.appendChild(clone);
  document.body.appendChild(popup);

  console.log(popup, 'popup');

  if (popup) {
    const btnClosePopup = document.getElementById('close-cart-popup');
    btnClosePopup.addEventListener('click', () => {
      clearInterval(interval);
      popup.remove();
    });
  }
}