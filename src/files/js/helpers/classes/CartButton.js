export class CartButton {
  #id;

  constructor(id) {
    this.#id = id;
  }

  async setCard(path) {
    try {
      const response = await fetch(`${path}?id=${this.#id}`, {
        method: 'POST'
      });
      console.log(`Fetching data from: ${path}?id=${this.#id}`);
      if (!response.ok) {
        throw new Error(`Ошибка: ${response.status}`);
      }
    } catch (error) {
      console.error('Ошибка при выполнении запроса:', error.message);
    }
  }


  getCard() {
    return this.#id;
  }

  addToCart() {
    console.log(`Товар с ID ${this.#id} добавлен в корзину.`);
  }

  removeFromCart() {
    console.log(`Товар с ID ${this.#id} удален из корзины.`);
  }
}