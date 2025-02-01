import { html, Component } from 'htm/preact';

export class CardCartButtonCounter extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: 1,
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

  render({ id, onAdd, onRemove, quantity, isRemoveButtonDisabled }) {
    const isDisabled = this.btnDisabledState(quantity);
    return html`
      <button
        type="button"
        class="button card-more__button card-more__button--min"
        data-id=${id}
        onClick=${onRemove}
        disabled=${isDisabled}
        aria-label="Убрать товар"
      >
        −
      </button>
      <div class="card-more__quantity y-button-primary">${quantity}</div>
      <button
        type="button"
        class="button card-more__button card-more__button--max"
        data-id=${id}
        onClick=${onAdd}
        aria-label="Добавить товар"
      >
        +
      </button>
    `;
  }
}
