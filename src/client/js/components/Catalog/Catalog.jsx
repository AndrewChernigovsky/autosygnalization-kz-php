import { html, Component } from 'htm/preact';

export class Catalog extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0,
    };
  }
  formatNumberWithSpaces(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
  }
  updateSessionStorage(id, quantity) {
    const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
    const index = localProducts.findIndex((product) => product.id === id);

    if (index !== -1) {
      localProducts[index].quantity = quantity;
      sessionStorage.setItem('cart', JSON.stringify(localProducts));
    }
  }

  render({ title, id, imageSrc, imageAlt, price, currency, checkout, index }) {
    return html` ${checkout
      ? html`
          <p class="checkout-info">
            <span class="checkout-info__count">${index + 1} </span>
            <span>Количество: ${this.props.quantity} </span>
            <span>${title} </span>
            <span>${price} ${currency}</span>
          </p>
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
                <span>${this.formatNumberWithSpaces(price)} ${currency}</span>
              </div>
            </div>
          </div>
        </article>`}`;
  }
}
