import { html, Component } from 'htm/preact';

export class CardCartButtonCounter extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0,
    };
  }

  render({ id, onAdd, onRemove, quantity, isRemoveButtonDisabled }) {
    return html`
      <button
        type="button"
        class="button card-more__button card-more__button--min"
        data-id=${id}
        onClick=${onRemove}
        disabled=${isRemoveButtonDisabled}
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
