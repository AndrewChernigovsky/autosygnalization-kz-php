import { html, Component } from 'htm/preact';

export class CartProductCard extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0
    };
  }

  handleRemoveToCart = () => {
    this.setState(prevState => {
      const newQuantity = prevState.quantity - 1;

      if (newQuantity < 0) {
        return { quantity: 0 };
      }

      this.props.onUpdateQuantity(this.props.id, newQuantity);
      this.updateSessionStorage(this.props.id, newQuantity);

      if (newQuantity === 0) {
        this.props.onRemove(this.props.id);
      }

      return { quantity: newQuantity };
    }, () => {
      console.log(`Товар с id ${this.props.id} убран из корзины. Текущее количество: ${this.state.quantity}`);
    });
  };

  handleAddToCart = () => {
    this.setState(prevState => {
      const newQuantity = prevState.quantity + 1;
      this.props.onUpdateQuantity(this.props.id, newQuantity);

      console.log(`Товар с id ${this.props.id} добавлен в корзину. Текущее количество: ${newQuantity}`);
      return { quantity: newQuantity };
    });
  };

  updateSessionStorage(id, quantity) {
    const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
    const index = localProducts.findIndex(product => product.id === id);

    if (index !== -1) {
      localProducts[index].quantity = quantity;
      sessionStorage.setItem('cart', JSON.stringify(localProducts));
    }
  }

  render({ title, id, imageSrc, imageAlt, price, currency, link }) {
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
        </div>
        <div class="product-card__buttons">
          <a class="button y-button-secondary" href=${link}>Купить</a>
          <div class="product-card__buttons-count">
            <button type="button" class="button y-button-primary cart-button" data-id=${id} onClick=${this.handleRemoveToCart} aria-label="Убрать товар">-</button>
            <div class="product-card__quantity">${this.state.quantity}</div>
            <button type="button" class="button y-button-primary cart-button" data-id=${id} onClick=${this.handleAddToCart} aria-label="Добавить товар">+</button>
          </div>
        </div>  
      </article>
    `;
  }
}
