import { html, Component } from 'htm/preact';

export class CartButton extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0
    };
  }

  handleRemoveToCart = () => {
    console.log("Корзина очищена");
    sessionStorage.removeItem('cart');
    this.setState({ quantity: 0 });
  };

  render() {
    return html`
      <button type="button" class="button y-button-secondary cart-button" onClick=${this.handleRemoveToCart}>Очистить корзину</button>
    `;
  }
}