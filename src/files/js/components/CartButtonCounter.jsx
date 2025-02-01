import { html, Component } from 'htm/preact';

export class CartButtonCounter extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0,
    };
  }

  btnDisabledState = (quantity) => {
    if (quantity >= 1) {
      console.log('Кнопка активна');
      return false;
    }
    console.log('Кнопка заблокирована');
    return true;
  };

  render({ id, onAdd, onRemove, quantity }) {
    const isDisabled = this.btnDisabledState(quantity);
    return html`
      <div class="product-card__buttons-count">
        <button
          type="button"
          class="button y-button-primary cart-button"
          data-id=${id}
          onClick=${isDisabled}
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
