import { html, Component } from 'htm/preact';
import { CartButton } from './CartButton.jsx';

export class CartCountTotal extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0
    };
  }

  render() {
    return html`
      <p>
        <span>${this.state.quantity}</span> товара/ов в корзине
      </p>
      <${CartButton} quantity=${this.state.quantity} />
    `;
  }
}
