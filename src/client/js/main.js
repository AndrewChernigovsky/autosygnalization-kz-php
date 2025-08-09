import { h, render } from 'preact';
import { html } from 'htm/preact';
import { SetWork, mountSetWork } from './components/Search.jsx';

const {
  feedbackForm,
  footer,
  fancyboxExist,
  searchExist,
  phoneButton,
  buttonPrint,
  buyBtn,
  buyBtns,
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
  cartButtons,
  addToCartButton,
  filtersAction,
  formOrder,
  deliveryModal,
  processForm,
  formReusable,
  policyModal,
} = {
  feedbackForm: document.getElementById('feedback-form'),
  footer: document.querySelector('footer'),
  fancyboxExist: document.querySelectorAll('[data-fancybox]'),
  searchExist: document.querySelector('.search'),
  phoneButton: document.querySelector('.phone-button'),
  selected: document.querySelector('.select-selected'),
  buttonPrint: document.getElementById('print-btn'),
  buyBtn: document.getElementById('buy-btn'),
  buyBtns: document.querySelectorAll('.buy-btn'),
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
  cartButtons: document.querySelectorAll('.cart-button'),
  addToCartButton: document.querySelector('.card-more__button-cart'),
  filtersAction: document.querySelector('.filter-form'),
  deliveryModal: document.getElementById('deliveryModal'),
  formOrder: document.querySelector('.cart-section'),
  processForm: document.querySelector('.form__main-form'),
  formReusable: document.querySelector('.popup.modal-form'),
  policyModal: document.querySelector('.policy-modal'),
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

    const currentPath = window.location.pathname;
    console.log(currentPath, 'PATH111');

    new CustomSelect(
      {
        selected: '.select-selected',
        item: '.select-items',
        options: '.select-item',
      },
      currentPath
    );
  }
  if (filtersAction != null) {
    const filtersAction = await import('./modules/filter.js');
    const FiltersAction = filtersAction.default;

    const currentPath = window.location.pathname;
    console.log(currentPath, 'PATH');

    new FiltersAction(
      {
        form: '.filter-form',
      },
      currentPath
    );
  }
  if (cartCounter != null || cartButtons.length > 0) {
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
    filterToggleMenu();
  }
  if (searchExist != null) {
    const { initSearch } = await import('./modules/search.js');
    initSearch();
  }
  // if (feedbackForm != null) {
  //   const { initValidate } = await import('./modules/initValidate.js');
  //   const { formHandler } = await import('./modules/form-handler.js');
  //   formHandler();
  //   initValidate();
  // }
  if (fancyboxExist.length > 0) {
    const { initFancybox } = await import('./modules/fancybox.js');
    initFancybox();
  }

  // Инициализация PDF превью
  const pdfPreviewExist = document.querySelectorAll('.pdf-preview-iframe');
  if (pdfPreviewExist.length > 0) {
    const { initPdfPreview } = await import('./modules/pdf-preview.js');
    initPdfPreview();
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

  if (buyBtns.length > 0) {
    const module = await import('./modules/buy.js');
    const initBuy = module.default;
    buyBtns.forEach((btn) => new initBuy(btn));
  }

  if (buttonsTabs != null) {
    const { showTabs } = await import('./modules/tabs.js');
    showTabs();
  }
  if (modalCart != null && cartButtons.length > 0) {
    const { setModalCart } = await import('./modules/cart-modal.js');
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

  if (deliveryModal != null) {
    const { initDeliveryModal } = await import('./modules/deliveryModal.js');
    initDeliveryModal();
  }
  if (document.getElementById('search-input')) {
    mountSetWork('search-input');
  }
  if (processForm != null) {
    const formHandler = await import('./modules/form-handler.js');
    const ProcessForm = formHandler.default;

    new ProcessForm({
      form: '.form__main-form.main-form',
    });
  }
  if (formReusable != null) {
    const formReusable = await import('./modules/form-reusable.js');
    const FormReusable = formReusable.default;

    new FormReusable({
      container: '.popup.modal-form',
      form: '.form__main-form',
    });
  }

  if (policyModal != null) {
    const { createPolicyModal, loadPolicyDocument } = await import(
      './modules/policy-modal.js'
    );
    const modal = createPolicyModal('Политика конфиденциальности');
    const content = await loadPolicyDocument('./files/docs/policy.txt');
    modal.setContent(content);
  }

  const forms = document.querySelectorAll('form');

  forms.forEach((form) => {
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton && submitButton.disabled === true) {
      submitButton.disabled = false;
    }
  });
}

document.addEventListener('DOMContentLoaded', loadModules);
