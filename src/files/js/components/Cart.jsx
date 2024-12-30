import { render, Component } from 'preact';
import { html } from 'htm/preact';
import { CartProductCard } from './CartProductCard.jsx';
import { CartCountTotal } from './CartCountTotal.jsx';

const data = { data: 'true' };
const cartCounter = document.querySelector('.cart .counter');
const costTotal = document.getElementById('cost-total');
export class Cart extends Component {
  constructor(props) {
    super(props);
    this.state = {
      totalQuantity: 0,
      totalCost: 0,
      products: []
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
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
      .then(response => response.json())
      .then(products => {
        this.setState({ products });
        this.renderProducts(products);
      })
      .catch(error => console.error('Error:', error));
  }

  renderProducts(products) {
    const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
    const productsContainerCart = document.querySelector('.cart-section__products');

    const cardComponents = [];

    const handleRemoveProduct = (id) => {
      const index = localProducts.findIndex(localProduct => localProduct.id === id);
      if (index !== -1) {
        localProducts.splice(index, 1);
        sessionStorage.setItem('cart', JSON.stringify(localProducts));
        this.updateTotalQuantity();
        this.renderProducts(products);
      }
    };

    const handleUpdateQuantity = (id, newQuantity) => {
      const updatedProducts = localProducts.map(localProduct => {
        if (localProduct.id === id) {
          return { ...localProduct, quantity: newQuantity };
        }
        return localProduct;
      }).filter(product => product.quantity > 0);

      sessionStorage.setItem('cart', JSON.stringify(updatedProducts));
      this.updateTotalQuantity(updatedProducts);
      this.renderProducts(products);
    };

    Object.values(products.category).forEach(productList => {
      productList.forEach(product => {
        const matchingProduct = localProducts.find(localProduct => localProduct.id === product.id);

        if (matchingProduct) {
          product.quantity = matchingProduct.quantity;

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
            />`;
          cardComponents.push(cardElement);
        } else {
          product.quantity = 0;
        }
      });
    });

    if (productsContainerCart) {
      render(html`${cardComponents}`, productsContainerCart);
    }

    this.updateTotalQuantity();
  }

  updateTotalQuantity() {
    const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
    const totalQuantity = localProducts.reduce((acc, product) => acc + product.quantity, 0);
    const totalCost = localProducts.reduce((acc, product) => acc + (Number(product.price) * product.quantity), 0);

    cartCounter.textContent = totalQuantity;
    this.setState({ totalQuantity, totalCost }, () => {
      if (costTotal) {
        costTotal.textContent = this.state.totalCost.toFixed(2);

      }
    });
  }

  render() {
    const head = document.querySelector('.cart-section__head');
    if (head) {
      const wrapper = html`
        <div>
          <${CartCountTotal} quantity=${this.state.totalQuantity} onClear=${this.handleClearCart} />
        </div>
      `;
      render(wrapper, head);
    }
    return null;
  }

  handleClearCart = () => {
    console.log("Корзина очищена");
    sessionStorage.removeItem('cart');
    this.setState({ totalQuantity: 0, totalCost: 0, products: [] });
    this.renderProducts({ category: [] });
  };
}
