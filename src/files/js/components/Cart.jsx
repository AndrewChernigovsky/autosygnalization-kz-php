import { render } from 'preact';
import { html } from 'htm/preact';
import { CartProductCard } from './components/CartProductCard.jsx';
import { CartCountTotal } from './components/CartCountTotal.jsx';


const data = { data: 'true' };
fetch('/dist/files/php/data/products.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(data)
})
  .then(response => response.json())
  .then(products => {
    renderProducts(products)
  })
  .catch(error => console.error('Error:', error));

function renderProducts(products) {
  const localProducts = JSON.parse(sessionStorage.getItem('cart')) || [];
  const productsContainerCart = document.querySelector('.cart-section__products');

  if (productsContainerCart) {
    productsContainerCart.innerHTML = '';
  }

  const cardComponents = [];

  const handleRemoveProduct = (id) => {
    const index = cardComponents.findIndex(card => card.props.id === id);
    if (index !== -1) {
      cardComponents.splice(index, 1);
      render(html`${cardComponents}`, productsContainerCart);
    }
  };


  Object.values(products.category).forEach(productList => {
    productList.forEach(product => {
      const matchingProduct = localProducts.find(localProduct => localProduct.id === product.id);

      if (matchingProduct) {
        console.log(`Найдено совпадение: ${product.title}, Количество в корзине: ${matchingProduct.quantity}`);
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
}

const head = document.querySelector('.cart-section__head');
if (head) {
  render(html`<${CartCountTotal} quantity=${0} />`, head);
}