import FormatNumber from '../helpers/classes/FormatNumber';

let modalInterval = null;
let currentPopup = null; // Глобальная переменная для хранения текущего модального окна

export function setModalCart() {
  const template = document.getElementById('cart-popup');

  const cartData = sessionStorage.getItem('cart');
  const cartItems = cartData ? JSON.parse(cartData) : [];

  const clone = document.importNode(template.content, true);
  const countItem = clone.querySelector('.cart-popup__count');
  const timerElement = clone.querySelector('.cart-popup__timer');
  const cost = clone.querySelector('.cart-popup__summary');
  const allCount = clone.querySelector('.cart-popup__all-count');
  const allCountElem = cartItems.reduce((total, product) => {
    return total + product.quantity;
  }, 0);

  let allQuantity = 0;
  let allCost = 0;

  cartItems.forEach((item) => {
    allQuantity += 1;
    allCost += item.price * item.quantity;
  });

  const format = new FormatNumber();
  countItem.textContent = `Товаров в корзине: ${allQuantity}`;
  cost.textContent = `Сумма: ${format.customFormatNumber(allCost)} ₸`;
  allCount.textContent = `Всего товаров: ${allCountElem}`;

  let timer = 5;
  timerElement.textContent = `Скрытие через ${timer} секунд`;

  // Очищаем предыдущий интервал, если он существует
  if (modalInterval) {
    clearInterval(modalInterval);
    modalInterval = null;
  }

  // Удаляем предыдущее модальное окно, если оно существует
  if (currentPopup) {
    currentPopup.remove();
    currentPopup = null;
  }

  // Создаем новый интервал
  modalInterval = setInterval(() => {
    timer--;
    timerElement.textContent = `Скрытие через ${timer} секунд`;

    if (timer <= 0) {
      clearInterval(modalInterval);
      modalInterval = null; // Сбрасываем интервал
      if (currentPopup) {
        currentPopup.remove();
        currentPopup = null;
      }
    }
  }, 1000);

  const popup = document.createElement('div');
  popup.classList.add('cart-popup-container');
  popup.appendChild(clone);
  document.body.appendChild(popup);

  currentPopup = popup; // Сохраняем текущее модальное окно

  console.log(popup, 'popup');

  const btnClosePopup = popup.querySelector('#close-cart-popup');
  if (btnClosePopup) {
    btnClosePopup.addEventListener('click', () => {
      clearInterval(modalInterval); // Очищаем интервал
      modalInterval = null; // Сбрасываем интервал
      if (currentPopup) {
        currentPopup.remove(); // Удаляем модальное окно
        currentPopup = null;
      }
    });
  }
}
