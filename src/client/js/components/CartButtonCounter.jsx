import { html, Component } from 'htm/preact';

export class CartButtonCounter extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0,
    };
  }

  render({ id, onAdd, onRemove, quantity }) {
    return html`
      <div class="product-card__buttons-count">
        <button
          type="button"
          class="button y-button-primary cart-button"
          data-id=${id}
          onClick=${onRemove}
          aria-label="Убрать товар"
        >
          -
        </button>
        <div class="product-card__quantity">${quantity}</div>
        <button
          type="button"
          class="button y-button-primary cart-button"
          data-id=${id}
          onClick=${onAdd}
          aria-label="Добавить товар"
        >
          +
        </button>
      </div>
    `;
  }
}
