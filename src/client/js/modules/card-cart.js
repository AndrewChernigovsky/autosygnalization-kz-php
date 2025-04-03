import { render } from 'preact';
import { html } from 'htm/preact';
import { CardProduct } from '../components/CardCart/CardProduct.jsx';

const cardMoreButton = document.querySelector('.card-more__button-cost');

export function renderCardButton() {
  if (cardMoreButton) {
    const id = cardMoreButton.dataset.id;
    const price = cardMoreButton.dataset.cost;
    render(html`<${CardProduct} id=${id} cost=${price} />`, cardMoreButton);
  }
}


