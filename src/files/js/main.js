import { render } from 'preact';
import { html } from 'htm/preact';
import { Card } from './card.jsx';

const {
  feedbackForm,
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
  resetCartButton
} = {
  feedbackForm: document.getElementById("feedback-form"),
  fancyboxExist: document.querySelectorAll("[data-fancybox]"),
  searchExist: document.querySelector(".search"),
  phoneButton: document.querySelector(".phone-button"),
  buttonPrint: document.getElementById("print-btn"),
  buyBtn: document.getElementById("buy-btn"),
  minValue: document.getElementById('minValue'),
  filterBtn: document.getElementById("filter-btn"),
  buttonsTabs: document.querySelectorAll('.tab__button'),
  menuButton: document.querySelector('.menu-toggle'),
  swiper: document.querySelector('.swiper'),
  cartCounter: document.querySelector('.cart .counter'),
  resetCartButton: document.getElementById('reset-cart')
};
function renderApp() {
  render(html`<${Card} title="Hello!" />`, document.body);
}

async function loadModules() {
  if (menuButton != null) {
    const { toToggleMenu } = await import("./modules/menu-burger.js");
    toToggleMenu();
  }
  if (swiper != null) {
    const { initSwiper } = await import("./modules/swiper.js");
    initSwiper();
  }
  if (phoneButton != null) {
    const { toggleList } = await import("./modules/footer-menu.js");
    toggleList();
  }
  if (cartCounter != null) {
    const { cartButtonHandler } = await import("./modules/cart-button.js");
    cartButtonHandler();
  }
  if (phoneButton != null) {
    const { initPhone } = await import("./modules/phone-button.js");
    initPhone();
  }
  if (filterBtn != null) {
    const { filterToggleMenu } = await import("./modules/filter.js");
    filterToggleMenu();
  }
  if (searchExist != null) {
    const { initSearch } = await import("./modules/search.js");
    initSearch();
  }
  if (feedbackForm != null) {
    const { initValidate } = await import("./modules/initValidate.js");
    initValidate();
  }
  if (fancyboxExist.length > 0) {
    const { initFancybox } = await import("./modules/fancybox.js");
    initFancybox();
  }
  if (minValue) {
    const { initializeRangeFilter } = await import("./modules/filter-cost.js");
    initializeRangeFilter('.filter-cost__range--min', '.filter-cost__range--max', 'minValue', 'maxValue');
  }
  if (buttonPrint) {
    const module = await import("./modules/print-contacts.js");
    const PrintDocument = module.default;
    new PrintDocument(buttonPrint);
  }
  if (resetCartButton) {
    const module = await import("./modules/reset-cart.js");
    const ResetCart = module.default;
    new ResetCart(resetCartButton);
  }
  if (buyBtn != null) {
    const module = await import("./modules/buy.js");
    const initBuy = module.default;
    new initBuy(buyBtn);
  }
  if (buttonsTabs != null) {
    const { showTabs } = await import("./modules/tabs.js");
    showTabs();
  }
}

document.addEventListener("DOMContentLoaded", loadModules);

const data = { data: 'true' };
fetch('/dist/files/php/data/products.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(data)
})
  .then(response => response.json())
  .then(products => {
    renderProducts(products)
  })
  .catch(error => console.error('Error:', error));


const template = document.getElementById('product-template');

function renderProducts(products) {
  if (template) {
    const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
    const productsContainerCart = document.querySelector('.cart-section__products');

    if (productsContainerCart) {
      productsContainerCart.innerHTML = '';
    }

    const cardComponents = [];

    Object.values(products.category).forEach(productList => {
      productList.forEach(product => {
        const matchingProduct = localProducts.find(localProduct => localProduct.id === product.id);

        if (matchingProduct) {
          console.log(`Найдено совпадение: ${product.title}, Количество в корзине: ${matchingProduct.quantity}`);
          product.quantity = matchingProduct.quantity;

          const cardElement = html`<${Card} 
            title=${product.title} 
            id=${product.id} 
            imageSrc=${product.gallery[0]} 
            imageAlt=${product.title} 
            price=${product.price} 
            currency=${product.currency} 
            link=${product.link} 
            quantity=${product.quantity} 
          />`;

          cardComponents.push(cardElement);
        } else {
          product.quantity = 0;
        }
      });
    });

    if (productsContainerCart) {
      render(html`${cardComponents}`, productsContainerCart);
    }
  }
}
