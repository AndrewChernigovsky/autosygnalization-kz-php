import { html, Component } from 'htm/preact';

export class CardCartButton extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    return html`
      <button
        type="button"
        class="button card-more__button card-more__button--min"
        aria-label="Убрать товар"
      ></button>
      <div class="card-more__quantity y-button-primary">1</div>
      <button
        type="button"
        class="button card-more__button card-more__button--max"
        aria-label="Добавить товар"
      ></button>
    `;
  }
}