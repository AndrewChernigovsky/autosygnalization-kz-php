import { html, Component } from 'htm/preact';
import { CardCartButtonCounter } from './CardCartButtonCounter.jsx';

export class CardProduct extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0,
      isRemoveButtonDisabled: false,
      price: 0,
    };
  }

  componentDidMount() {
    const priceElement = document.querySelector('.product-card__price');
    if (priceElement) {
      const price = Number(priceElement.dataset.price);
      if (!isNaN(price)) {
        this.setState({ price }, this.calculateTotalCost);
      } else {
        console.error(
          'Price is not a valid number:',
          priceElement.dataset.price
        );
      }
    } else {
      console.error('Price element not found');
    }
  }

  removeToCart = () => {
    this.setState(
      (prevState) => {
        const newQuantity = Math.max(prevState.quantity - 1, 0);
        this.updateSessionStorage(this.props.id, newQuantity);

        if (newQuantity === 0) {
          this.setState({ isRemoveButtonDisabled: true });
          if (typeof this.props.onRemove === 'function') {
            this.props.onRemove(this.props.id);
          } else {
            console.warn('onRemove is not a function');
          }
        }

        return { quantity: newQuantity };
      },
      () => {
        this.calculateTotalCost();
      }
    );
  };

  addToCart = () => {
    this.setState(
      (prevState) => {
        const newQuantity = prevState.quantity + 1;
        this.updateSessionStorage(this.props.id, newQuantity);

        if (newQuantity > 0) {
          this.setState({ isRemoveButtonDisabled: false });
        }

        return { quantity: newQuantity };
      },
      () => {
        this.calculateTotalCost();
      }
    );
  };

  calculateTotalCost() {
    const quantity = Number(this.state.quantity);
    const price = this.state.price;

    if (isNaN(quantity) || isNaN(price)) {
      console.error('Invalid quantity or price:', quantity, price);
      return;
    }

    const totalCost = quantity * price;
    const costTotalElement = document.querySelector('#cost-total');
    if (costTotalElement) {
      costTotalElement.textContent = totalCost.toFixed(2) + ' ₸';
    }
  }

  updateSessionStorage(id, quantity) {
    const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
    const index = localProducts.findIndex((product) => product.id === id);

    if (index !== -1) {
      localProducts[index].quantity = quantity;
      sessionStorage.setItem('cart', JSON.stringify(localProducts));
    }
  }

  render({ title, id, currency, checkout }) {
    return html`
      ${checkout
        ? html`
            <li class="checkout-info">
              <span>${title} </span>
              <div>
                <span>Количество:</span>
                <span>${this.state.quantity} </span>
              </div>
              <div>
                <p>
                  Цена:
                  <span>${this.state.price} </span>
                  <span>${currency} </span>
                </p>
              </div>
            </li>
          `
        : html`
            <${CardCartButtonCounter}
              id=${id}
              onRemove=${this.removeToCart}
              onAdd=${this.addToCart}
              quantity=${this.state.quantity}
              isRemoveButtonDisabled=${this.state.isRemoveButtonDisabled}
            />
          `}
    `;
  }
}
