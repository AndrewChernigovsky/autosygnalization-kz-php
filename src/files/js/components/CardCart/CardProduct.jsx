import { html, Component } from 'htm/preact';
import { CardCartButtonCounter } from './CardCartButtonCounter.jsx';
import { ProductAPI } from './../../modules/api/getProduct.js';

export class CardProduct extends Component {
  constructor(props) {
    super(props);
    this.state = {
      quantity: props.quantity || 0,
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
  }

  sendSessionCart(btn, action) {
    let products = JSON.parse(sessionStorage.getItem('cart')) || [];

    if (this.cartCounter) {
      const currentCount = products.reduce(
        (total, product) => total + product.quantity,
        0
      );
      this.cartCounter.textContent = currentCount;

      const productApi = new ProductAPI();
      productApi.createProducts();

      const productId = btn.parentElement.dataset.id;
      const productPrice = btn.parentElement.dataset.cost;
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
            products = [];
          }
        }
      }

      sessionStorage.setItem('cart', JSON.stringify(products));
      productApi.addProduct(productId);

      const newCount = products.reduce((total, product) => {
        return total + product.quantity;
      }, 0);

      this.cartCounter.textContent = newCount;

      productApi
        .sendCart(products)
        .then((responseData) => {
          console.log('Данные успешно отправлены:', responseData);
        })
        .catch((error) => {
          console.error('Ошибка при отправке данных:', error);
        });
    }
  }

  removeToCart = (e) => {
    const btn = e.target;
    this.setState(
      (prevState) => {
        const newQuantity = Math.max(prevState.quantity - 1, 0);
        this.updateSessionStorage(this.props.id, newQuantity, btn, 'remove');

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

  addToCart = (e) => {
    const btn = e.target;
    this.setState(
      (prevState) => {
        const newQuantity = prevState.quantity + 1;
        this.updateSessionStorage(this.props.id, newQuantity, btn, 'add');

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

  updateSessionStorage(id, quantity, btn, action) {
    this.sendSessionCart(btn, action);
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
        onRemove=${this.removeToCart}
        onAdd=${this.addToCart}
        quantity=${this.state.quantity}
        isRemoveButtonDisabled=${this.state.isRemoveButtonDisabled}
      />
    `;
  }
}
