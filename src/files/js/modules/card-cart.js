import { render } from 'preact';
import { html } from 'htm/preact';
import { CardCartButton } from '../components/CardCart/CardCartButton.jsx';

const cardMoreButton = document.querySelector('.card-more__button-cost');

export function renderCardButton () {
  if(cardMoreButton) {
    render(html`<${CardCartButton} />`, cardMoreButton);
  }
}


