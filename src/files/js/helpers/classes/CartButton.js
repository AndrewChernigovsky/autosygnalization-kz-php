import { ProductAPI } from "../../modules/api/getProduct.js";
export class CartButton {
  PRODUCTION
  productApi

  constructor() {
    this.PRODUCTION = window.location.href.includes('/dist/');
    this.productApi = new ProductAPI();
  }

  async updateProductQuantity(id) {
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/products/products.php`;
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: id })
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        alert(data.message);
        console.log(data.product);
      })
      .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
      });
  }
  async updateCart() {
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/sessions/get_session_data.php`;
    fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        console.log(data);
        if (data.message) {
          alert(data.message);
        } else {
          let totalQuantity = 0;
          for (const productId in data) {
            totalQuantity += data[productId].quantity;
          }
          const cart = document.querySelector('.cart .link .count');
          if (cart) cart.textContent = totalQuantity;
        }
      })
      .catch(error => {
        console.error('Error fetching session data:', error);
      });
  }
}