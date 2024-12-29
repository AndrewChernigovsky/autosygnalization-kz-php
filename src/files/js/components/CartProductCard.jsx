import { html, Component } from 'htm/preact';

export class CartProductCard extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0
    };
  }

  setState(newState) {
    super.setState(newState);
  }


  handleRemoveToCart = () => {
    this.setState(prevState => {
      const newQuantity = prevState.quantity - 1;
      if (newQuantity < 0) return { quantity: 0 }

      if (newQuantity === 0) {
        this.props.onRemove(this.props.id);
      }

      return { quantity: newQuantity };
    }, () => {
      console.log(`Товар с id ${this.props.id} убран из корзины. Текущее количество: ${this.state.quantity}`);
    });
  };
  handleAddToCart = () => {
    this.setState(prevState => ({ quantity: prevState.quantity + 1 }));

    console.log(`Товар с id ${this.props.id} добавлен в корзину. Текущее количество: ${this.state.quantity + 1}`);
  };

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
          <button type="button" class="button  y-button-primary cart-button" data-id=${id} onClick=${this.handleRemoveToCart} aria-hidden="Убрать товар">-</button>
          <button type="button" class="button  y-button-primary cart-button" data-id=${id} onClick=${this.handleAddToCart} aria-hidden="Добавить товар">+</button>
        </div>  
          <div class="quantity">Количество: ${this.state.quantity}</div>
        </div>  
      </article>
    `;
  }
}
