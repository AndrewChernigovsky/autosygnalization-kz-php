import { html, Component } from 'htm/preact';

export class OfferCartButton extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0
    };
  }

  renderModal() {
    return html`
    <div class="fast-cart-order">

    </div>
    <button type="submit">Заказать</button>
  `;
  }

  render({ }) {
    return html`
      <button type="button" id="fast-cart-order">Быстрый заказ</button>
    `;
  }
}
