import { html, Component } from 'htm/preact';
import { CardCartButtonCounter } from './CardCartButtonCounter.jsx';
import { compileString } from 'sass';

export class CardProduct extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: 1,
      isRemoveButtonDisabled: false,
      price: props.cost || 0,
    };
    this.cartCounter = document.querySelector('.cart .counter');
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

    this.updateQuantityFromCart();

    const addToCartButton = document.querySelector('.card-more__button-cart');
    if (addToCartButton) {
      addToCartButton.addEventListener('click', this.handleAddToCart);
    }
  }

  componentWillUnmount() {
    const addToCartButton = document.querySelector('.card-more__button-cart');
    if (addToCartButton) {
      addToCartButton.removeEventListener('click', this.handleAddToCart);
    }
  }

  updateQuantityFromCart() {
    const products = JSON.parse(sessionStorage.getItem('cart')) || [];
    const existingProduct = products.find(
      (product) => product.id === this.props.id
    );

    if (existingProduct) {
      this.setState({ quantity: existingProduct.quantity });
    }
  }

  handleAddToCart = (e) => {
    const btn = e.currentTarget;
    this.setState(
      (prevState) => {
        const newQuantity = prevState.quantity;
        this.updateSessionStorage(this.props.id, newQuantity, btn, 'add', true);
        return { quantity: newQuantity };
      },
      () => {
        this.calculateTotalCost();
      }
    );
  };

  sendSessionCart(btn, action, cart) {
    let products = JSON.parse(sessionStorage.getItem('cart')) || [];
    if (this.cartCounter) {
      const currentCount = products.reduce(
        (total, product) => total + product.quantity,
        0
      );
      console.log('здесь');

      if (!cart) {
        this.cartCounter.textContent = currentCount;
        console.log('здесь');
      }

      const productId = cart ? btn.dataset.id : btn.parentElement.dataset.id;
      const productPrice = cart
        ? btn.dataset.cost
        : btn.parentElement.dataset.cost;
      const existingProduct = products.find(
        (product) => product.id === productId
      );

      if (action === 'add') {
        if (existingProduct) {
          existingProduct.quantity += 1;
        } else {
          products.push({
            id: productId,
            quantity: 1,
            price: productPrice,
          });
        }
      } else if (action === 'remove') {
        if (existingProduct) {
          existingProduct.quantity -= 1;
          if (existingProduct.quantity <= 0) {
            products = products.filter((product) => product.id !== productId);
          }
        }
      }

      sessionStorage.setItem('cart', JSON.stringify(products));

      let numberUniqueId = products.map((product) => product.id).length;

      const newCount = products.reduce((total, product) => {
        return total + product.quantity;
      }, 0);

      this.cartCounter.textContent = numberUniqueId; // здесь на карточки
    }
  }

  removeToCart = (e) => {
    const btn = e.currentTarget;
    this.setState(
      (prevState) => {
        const newQuantity = Math.max(prevState.quantity - 1, 0);
        this.updateSessionStorage(this.props.id, newQuantity, btn, 'remove');

        if (newQuantity === 0) {
          this.setState({ isRemoveButtonDisabled: true });
          if (typeof this.props.removeCount === 'function') {
            this.props.removeCount(this.props.id);
          } else {
            console.warn('removeCount is not a function');
          }
        }

        return { quantity: newQuantity };
      },
      () => {
        this.calculateTotalCost();
      }
    );
  };

  addToCart = (e) => {
    const btn = e.currentTarget;
    this.setState(
      (prevState) => {
        const newQuantity = prevState.quantity + 1;
        // this.updateSessionStorage(this.props.id, newQuantity, btn, 'add');

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

  updateSessionStorage(id, quantity, btn, action, cart = false) {
    this.sendSessionCart(btn, action, cart);
    const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
    const index = localProducts.findIndex((product) => product.id === id);

    if (index !== -1) {
      localProducts[index].quantity = quantity;
      sessionStorage.setItem('cart', JSON.stringify(localProducts));
    }
  }

  render() {
    return html`
      <${CardCartButtonCounter}
        id=${this.id}
        removeCount=${this.removeToCart}
        addCount=${this.addToCart}
        quantity=${this.state.quantity}
        isRemoveButtonDisabled=${this.state.isRemoveButtonDisabled}
      />
    `;
  }
}
