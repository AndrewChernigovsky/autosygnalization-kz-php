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

  render({ id, addCount, removeCount, quantity }) {
    const isDisabled = this.btnDisabledState(quantity);
    return html`
      <button
        type="button"
        class="button card-more__button card-more__button--min"
        data-id=${id}
        onClick=${removeCount}
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
        onClick=${addCount}
        aria-label="Добавить товар"
      >
        +
      </button>
    `;
  }
}
