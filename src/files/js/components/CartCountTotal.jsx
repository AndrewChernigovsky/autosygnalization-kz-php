import { html, Component } from 'htm/preact';
import { CartButton } from './CartButton.jsx';

export class CartCountTotal extends Component {
  render() {
    return html`
      <div>
        ${this.props.checkout
        ? html`<p>товары в заказе:</p>`
        : html`
            <p>
              <span>${this.props.quantity}</span> товара/ов в корзине
            </p>
            <${CartButton} quantity=${this.props.quantity} onClick=${this.props.onClear} />
          `}
      </div>
    `;
  }
}
