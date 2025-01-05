import { html, Component } from 'htm/preact';

export class CartButton extends Component {
  render() {
    return html`
      <button type="button" class="button y-button-secondary cart-button" onClick=${this.props.onClick}>Очистить корзину</button>
    `;
  }
}