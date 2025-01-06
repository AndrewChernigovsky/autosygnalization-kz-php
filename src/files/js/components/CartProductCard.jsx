import { html, Component } from 'htm/preact';
import { CartButtonCounter } from './CartButtonCounter.jsx';

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
      console.log(newQuantity, 'newQuantity CartProductCard.jsx');

      this.props.onUpdateQuantity(this.props.id, newQuantity);
      this.updateSessionStorage(this.props.id, newQuantity);

      console.log(this.props.id, 'ID PROPS CartProductCard.jsx');
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

  render({ title, id, imageSrc, imageAlt, price, currency, order = false }) {
    return html`
      <article id=${id} class='product-card'>
        <div class="product-card__bg">
          <img src=${imageSrc} alt=${imageAlt} width="300" height="250"/>
        </div>
        <div class="product-card__body">
          <div class="product-card__head">
            <h3>${title}</h3>
            <div class="price">
              <span>Цена: </span>
              <span>${price} ${currency}</span>
            </div>
          </div>
        </div>
        <div class="product-card__buttons cart-btn">
          <${CartButtonCounter} id=${id} onRemove=${this.handleRemoveToCart} onAdd=${this.handleAddToCart} quantity=${this.state.quantity}/>
        </div>  
      </article>
    `;
  }
}
