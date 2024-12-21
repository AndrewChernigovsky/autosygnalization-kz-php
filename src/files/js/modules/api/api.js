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

  async addProduct(id) {
    const url = `${this.PRODUCTION ? '/dist/' : '/'}files/php/api/products/add_product_count.php`;

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
}