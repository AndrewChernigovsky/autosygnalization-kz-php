import { render, Component } from 'preact';
import { html } from 'htm/preact';
import { CartProductCard } from './CartProductCard.jsx';
import { CartCountTotal } from './CartCountTotal.jsx';

const data = { data: 'true' };
const cartCounter = document.querySelector('.cart .counter');
const costTotal = document.querySelectorAll('.cost-total');
const quantityTotal = document.querySelectorAll('.quantity-total');
export class Cart extends Component {
  constructor(props) {
    super(props);
    this.state = {
      totalQuantity: 0,
      totalCost: 0,
      products: [],
      errorMessage: null,
    };
  }

  componentDidMount() {
    this.fetchProducts();
  }

  fetchProducts() {
    const PRODUCTION = window.location.href.includes('/dist/');
    const url = `${PRODUCTION ? '/dist/' : '/'}files/php/data/products.php`;

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((products) => {
        this.setState({ products });
        this.renderProducts(products);
      })
      .catch((error) => {
        console.error('Error:', error);
        this.setState({ errorMessage: 'Не удалось загрузить продукты' });
      });
  }

  renderProducts(products) {
    const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
    const productsContainerCart = document.querySelectorAll(
      '.cart-section__products'
    );

    const cardComponents = [];

    const handleRemoveProduct = (id) => {
      const index = localProducts.findIndex(
        (localProduct) => localProduct.id === id
      );
      console.log(index, 'index Cart.jsx');
      if (index !== -1) {
        localProducts.splice(index, 1);
        sessionStorage.setItem('cart', JSON.stringify(localProducts));
        this.updateTotalQuantity();
        this.renderProducts(localProducts);
      }
    };

    const handleUpdateQuantity = (id, newQuantity) => {
      const updatedProducts = localProducts
        .map((localProduct) => {
          if (localProduct.id === id) {
            return { ...localProduct, quantity: newQuantity };
          }
          return localProduct;
        })
        .filter((product) => product.quantity > 0);

      sessionStorage.setItem('cart', JSON.stringify(updatedProducts));
      this.updateTotalQuantity(updatedProducts);
      this.renderProducts(products);
    };

    Object.values(products.category).forEach((productList) => {
      productList.forEach((product, index) => {
        const matchingProduct = localProducts.find(
          (localProduct) => localProduct.id === product.id
        );

        if (matchingProduct) {
          product.quantity = matchingProduct.quantity;

          let checkout = false;
          productsContainerCart.forEach((container) => {
            if (container.classList.contains('checkout')) {
              checkout = true;
            }
          });

          const cardElement = html`<${CartProductCard}
            title=${product.title}
            id=${product.id}
            imageSrc=${product.gallery[0]}
            imageAlt=${product.title}
            price=${product.price}
            currency=${product.currency}
            link=${product.link}
            quantity=${product.quantity}
            onRemove=${handleRemoveProduct}
            onUpdateQuantity=${handleUpdateQuantity}
            checkout=${checkout}
            index=${index}
          />`;
          cardComponents.push(cardElement);
        } else {
          product.quantity = 0;
        }
      });
    });

    if (productsContainerCart.length > 0) {
      const totalQuantity = localProducts.reduce(
        (acc, el) => acc + el.quantity,
        0
      );
      this.setState({ totalQuantity: totalQuantity }, () => {
        if (this.state.totalQuantity <= 0) {
          productsContainerCart.forEach((container) => {
            render(
              html`<p class="cart-section__count-products">
                Нет добавленных товаров
              </p>`,
              container
            );
          });
        } else {
          productsContainerCart.forEach((container) => {
            render(html`${cardComponents}`, container);

            if (container.classList.contains('checkout')) {
              render(html`${cardComponents}`, container);
            }
          });
        }
      });
    }

    this.updateTotalQuantity();
  }

  updateTotalQuantity() {
    const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
    const totalQuantity = localProducts.reduce(
      (acc, product) => acc + product.quantity,
      0
    );
    const totalCost = localProducts.reduce(
      (acc, product) => acc + Number(product.price) * product.quantity,
      0
    );
    cartCounter.textContent = totalQuantity;
    this.setState({ totalQuantity, totalCost }, () => {
      if (costTotal.length > 0) {
        costTotal.forEach((cost) => {
          cost.textContent = this.state.totalCost.toFixed(2) + ' ₸';
        });
      }
      if (quantityTotal.length > 0) {
        quantityTotal.forEach((quantity) => {
          quantity.textContent = this.state.totalQuantity;
        });
      }
    });
  }

  render() {
    const head = document.querySelector('.cart-section__head');

    if (head) {
      if (head.classList.contains('checkout')) {
        const wrapper = html`
          <${CartCountTotal}
            quantity=${this.state.totalQuantity}
            onClear=${this.handleClearCart}
            checkout=${true}
          />
        `;
        render(wrapper, head);
        return null;
      } else {
        const wrapper = html`
          <${CartCountTotal}
            quantity=${this.state.totalQuantity}
            onClear=${this.handleClearCart}
          />
        `;
        render(wrapper, head);
        return null;
      }
    }
  }

  handleClearCart = () => {
    sessionStorage.removeItem('cart');
    this.setState({ totalQuantity: 0, totalCost: 0, products: [] });
    this.renderProducts({ category: [] });
  };
}
