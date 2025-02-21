import { html, Component } from 'htm/preact';

export class CardCartButtonCounter extends Component {
  constructor(props) {
    super(props);

  }

  render({ id, addCount, removeCount, quantity }) {
    return html`
      <button
        type="button"
        class="button card-more__button card-more__button--min"
        data-id=${id}
        onClick=${removeCount}
        disabled=${quantity <= 1}
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
