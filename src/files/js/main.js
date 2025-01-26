import { render } from 'preact';
import { html } from 'htm/preact';

const {
  feedbackForm,
  footer,
  fancyboxExist,
  searchExist,
  phoneButton,
  buttonPrint,
  buyBtn,
  minValue,
  filterBtn,
  buttonsTabs,
  menuButton,
  swiper,
  cartCounter,
  resetCartButton,
  productsContainerCart,
  modalCart,
  selected,
  buyBtnFast,
  checkoutForm,
  filterCost,
  aside,
  catalog,
  cardMoreButton,
} = {
  feedbackForm: document.getElementById('feedback-form'),
  footer: document.querySelector('footer'),
  fancyboxExist: document.querySelectorAll('[data-fancybox]'),
  searchExist: document.querySelector('.search'),
  phoneButton: document.querySelector('.phone-button'),
  selected: document.querySelector('.select-selected'),
  buttonPrint: document.getElementById('print-btn'),
  buyBtn: document.getElementById('buy-btn'),
  minValue: document.getElementById('minValue'),
  filterBtn: document.getElementById('filter-btn'),
  buttonsTabs: document.querySelectorAll('.tab__button'),
  menuButton: document.querySelector('.menu-toggle'),
  swiper: document.querySelector('.swiper'),
  cartCounter: document.querySelector('.cart .counter'),
  resetCartButton: document.getElementById('reset-cart'),
  modalCart: document.getElementById('cart-popup'),
  productsContainerCart: document.querySelector('.cart-section__products'),
  buyBtnFast: document.getElementById('buy-fast-order'),
  checkoutForm: document.querySelector('.checkout-form__body'),
  filterCost: document.querySelector('.filter-cost'),
  aside: document.querySelector('.aside'),
  catalog: document.querySelector('.catalog__products'),
  cardMoreButton: document.querySelector('.card-more__button-cost'),
};

async function loadModules() {
  if (filterCost != null) {
    const module = await import('./modules/filter-cost.js');
    const newRangeSlider = module.default;
    new newRangeSlider('.filter-cost', {
      minValue: 100,
      maxValue: 1000,
      step: 10,
      gap: 100,
    });
  }

  if (aside != null && catalog != null) {
    const { renderCardsOffers } = await import(
      './modules/dynamic-offers-card.js'
    );
    renderCardsOffers();
  }
  if (menuButton != null) {
    const { toToggleMenu } = await import('./modules/menu-burger.js');
    toToggleMenu();
  }
  if (swiper != null) {
    const { initSwiper } = await import('./modules/swiper.js');
    initSwiper();
  }
  if (footer != null) {
    const { toggleList } = await import('./modules/footer-menu.js');
    toggleList();
  }
  if (selected != null) {
    const select = await import('./modules/select.js');
    const CustomSelect = select.default;
    const path = window.location.href.includes('parking-systems')
      ? 'files/php/pages/parking-systems/parking-systems.php'
      : 'files/php/pages/catalog/catalog.php';
    new CustomSelect(
      {
        selected: '.select-selected',
        item: '.select-items',
        options: '.select-item',
      },
      path
    );
  }
  if (cartCounter != null) {
    const { cartButtonHandler } = await import('./modules/cart-button.js');
    cartButtonHandler();
  }
  if (phoneButton != null) {
    const module = await import('./modules/initFormModal.js');
    const InitFormModal = module.default;
    new InitFormModal().initPhone();
  }
  if (buyBtnFast != null) {
    const module = await import('./modules/initFormModal.js');
    const InitFormModal = module.default;
    new InitFormModal().initBtnFast();
  }
  if (filterBtn != null) {
    const { filterToggleMenu } = await import('./modules/filter.js');
    const { saveChecbox } = await import('./modules/filter.js');
    filterToggleMenu();
    saveChecbox();
  }
  if (searchExist != null) {
    const { initSearch } = await import('./modules/search.js');
    initSearch();
  }
  if (feedbackForm != null) {
    const { initValidate } = await import('./modules/initValidate.js');
    initValidate();
  }
  if (fancyboxExist.length > 0) {
    const { initFancybox } = await import('./modules/fancybox.js');
    initFancybox();
  }
  if (minValue) {
    const { initializeRangeFilter } = await import('./modules/filter-cost.js');
    initializeRangeFilter(
      '.filter-cost__range--min',
      '.filter-cost__range--max',
      'minValue',
      'maxValue'
    );
  }
  if (buttonPrint) {
    const module = await import('./modules/print-contacts.js');
    const PrintDocument = module.default;
    new PrintDocument(buttonPrint);
  }
  if (resetCartButton) {
    const module = await import('./modules/reset-cart.js');
    const ResetCart = module.default;
    new ResetCart(resetCartButton);
  }
  if (buyBtn != null) {
    const module = await import('./modules/buy.js');
    const initBuy = module.default;
    new initBuy(buyBtn);
  }
  if (buttonsTabs != null) {
    const { showTabs } = await import('./modules/tabs.js');
    showTabs();
  }
  if (modalCart != null) {
    const { setModalCart } = await import('./modules/cart-modal.js');
    const cartButtons = document.querySelectorAll('.cart-button');
    cartButtons.forEach((btn) => btn.addEventListener('click', setModalCart));
  }
  if (productsContainerCart != null) {
    const { Cart } = await import('./components/Cart.jsx');
    render(html`<${Cart} />`, document.body);
  }
  if (checkoutForm != null) {
    const { CheckoutForm } = await import(
      './components/Checkout/CheckoutForm.jsx'
    );
    render(html`<${CheckoutForm} />`, checkoutForm);
  }

  if (cardMoreButton != null) {
    const { renderCardButton } = await import('./modules/card-cart.js');
    renderCardButton();
  }
}

document.addEventListener('DOMContentLoaded', loadModules);
