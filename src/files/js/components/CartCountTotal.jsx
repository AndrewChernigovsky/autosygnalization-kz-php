import { html, Component } from 'htm/preact';
import { CartButton } from './CartButton.jsx';

export class CartCountTotal extends Component {
  render() {
    return html`
      <p>
        <span>${this.props.quantity}</span> товара/ов в корзине
      </p>
      <${CartButton} quantity=${this.props.quantity} onClick=${this.props.onClear} />
    `;
  }
}
