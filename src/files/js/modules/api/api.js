export class API {
  PRODUCTION

  constructor() {
    this.PRODUCTION = window.location.href.includes('/dist/');
  }

  async getProduct(id) {
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/products/get_product.php`;

    try {
      const response = await fetch(url, {
        method: 'POST',
        body: JSON.stringify({ id: id })
      })
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const data = await response.json();
      console.log(data, 'DATA');
      return data
    }
    catch (err) {
      console.error('Не удалось обновить количество товаров: ', err);
    }
  }
  async getProductByCategory(category) {
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/products/get_by_category.php`;

    try {
      const response = await fetch(url, {
        method: 'POST',
        body: JSON.stringify({ category: category })
      })
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const data = await response.json();
      console.log(data, 'DATA');
      return data
    }
    catch (err) {
      console.error('Не удалось обновить количество товаров: ', err);
    }
  }
  async getProductAll() {
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/products/get_all_products.php`;

    try {
      const response = await fetch(url, {
        method: 'GET',
      })
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const data = await response.json();
      console.log(data, 'DATA');
      return data
    }
    catch (err) {
      console.error('Не удалось обновить количество товаров: ', err);
    }
  }
  async createProducts() {
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/products/products.php?data=create`;

    try {
      const response = await fetch(url, {
        method: 'GET',
      })
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const data = await response.json();
      console.log(data, 'DATA');
      return data
    }
    catch (err) {
      console.error('Не удалось создать товары: ', err);
    }
  }
  async addProduct(id) {
    console.log(id, 'ID');
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/products/products.php?data=add&id=${id}`;

    try {
      const response = await fetch(url, {
        method: 'POST',
        body: JSON.stringify({ id: id }),
      })

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const data = await response.json();
      console.log(data, 'DATA');
      return data
    }
    catch (err) {
      console.error('Не удалось обновить количество товаров: ', err);
    }
  }
  async sendCart(products) {
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/products/products.php?data=cart`;

    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(products)
      })

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const data = await response.json();
      console.log('Response data:', data);
      return data;
    }
    catch (err) {
      console.error('Не удалось отправить данные в базу данных: ', err);
    }
  }
  async clearCart() {
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/sessions/session-destroy.php`;
    if (sessionStorage.getItem('cart')) {
      sessionStorage.removeItem('cart');
    }
    try {
      const response = await fetch(url, {
        method: 'POST'
      })
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
    } catch (err) {
      console.error('Товары не удалились: ', err);
    }
  }
}