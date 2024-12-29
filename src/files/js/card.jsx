import { html, Component } from 'htm/preact';

export class Card extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0
    };
  } 3

  setState(newState) {
    super.setState(newState);
  }

  render({ title, id, imageSrc, imageAlt, price, currency, link, quantity }) {
    return html`
      <article id=${id} class='product-card'>
        <div class="product-card__bg">
          <img src=${imageSrc} alt=${imageAlt} width="300" height="250"/>
        </div>
        <div class="product-card__body">
        <div class="product-card__head">
        <h3>${title}</h3>
            <div class="price">Цена: ${price} ${currency}</div>
        </div>
        <div class="product-card__buttons">
          <a class="button y-button-secondary" href=${link}>Купить</a>
          <div class="cart-button" data-id=${id}>Добавить в корзину</div>
        </div>  
          <div class="quantity">Количество: ${this.state.quantity}</div>
        </div>  
      </article>
    `;
  }
}
