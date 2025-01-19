import { render } from 'preact';
import { html } from 'htm/preact';
import { OfferCard } from './../components/OfferCard.jsx';

const aside = document.querySelector('.aside');
const catalog = document.querySelector('.catalog__products');

let isMobile = null;
let resizeEventListener = null;
let containerElement = null;

function checkWindowSize() {
  const isMobileNow = window.innerWidth <= 1024;
  if (isMobileNow !== isMobile) {
    isMobile = isMobileNow;
    if (isMobile) {
      dynamicCardsRenders(true);
    } else {
      dynamicCardsRenders(false);
    }
  }
}

function createContainerElement(className) {
  const container = document.createElement('div');
  container.setAttribute('class', className);
  return container;
}

function renderOfferCard(containerElement) {
  render(html`<${OfferCard} />`, containerElement);
}

function destroyOfferCard(containerElement) {
  render(null, containerElement);
  containerElement.remove();
}

function dynamicCardsRenders(isMobile) {
  if (containerElement) {
    destroyOfferCard(containerElement);
  }

  containerElement = createContainerElement(isMobile ? 'catalog__offers' : 'aside__offers');
  const parentElement = isMobile ? catalog : aside;

  parentElement.appendChild(containerElement);
  renderOfferCard(containerElement);
}

function addResizeEventListener() {
  resizeEventListener = window.addEventListener('resize', checkWindowSize);
}

function removeResizeEventListener() {
  if (resizeEventListener) {
    window.removeEventListener('resize', checkWindowSize);
    resizeEventListener = null;
  }
}

export function renderCardsOffers() {
  checkWindowSize();
  addResizeEventListener();
}

export function unmountCardsOffers() {
  removeResizeEventListener();
  if (containerElement) {
    destroyOfferCard(containerElement);
  }
}