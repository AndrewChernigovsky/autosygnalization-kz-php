import { html, Component } from 'htm/preact';
import { CartButtonCounter } from './CartButtonCounter.jsx';

export class CartProductCard extends Component {
  handleRemoveToCart = () => {
    const newQuantity = Math.max(this.props.quantity - 1, 0);
    this.props.onUpdateQuantity(this.props.id, newQuantity);

    if (newQuantity === 0) {
      this.props.onRemove(this.props.id);
    }
  };

  handleAddToCart = () => {
    const newQuantity = this.props.quantity + 1;
    this.props.onUpdateQuantity(this.props.id, newQuantity);
  };

  render({
    title,
    id,
    imageSrc,
    imageAlt,
    price,
    currency,
    checkout,
    quantity,
  }) {
    return html` ${checkout
      ? html`
          <li class="checkout-info">
            <span>${title} </span>
            <div>
              <span>Количество:</span>
              <span>${quantity} </span>
            </div>
            <div>
              <p>
                Цена:
                <span>${price} </span>
                <span>${currency} </span>
              </p>
            </div>
          </li>
        `
      : html` <article id=${id} class="product-card">
          <div class="product-card__bg">
            <img src=${imageSrc} alt=${imageAlt} width="300" height="250" />
          </div>
          <div class="product-card__body">
            <div class="product-card__head">
              <h3>${title}</h3>
              <div class="price">
                <span>Цена: </span>
                <span>${price} ${currency}</span>
              </div>
            </div>
            <div class="product-card__buttons cart-btn">
              <${CartButtonCounter}
                id=${id}
                onRemove=${this.handleRemoveToCart}
                onAdd=${this.handleAddToCart}
                quantity=${quantity}
              />
            </div>
          </div>
        </article>`}`;
  }
}
