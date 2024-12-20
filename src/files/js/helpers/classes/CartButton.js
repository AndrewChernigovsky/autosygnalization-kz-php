export class CartButton {
  id;

  constructor(id) {
    this.id = id;
  }

  async setCard(path) {
    try {
      const response = await fetch(path, {
        method: "POST",
        headers: {
          'Content-Type': 'application/json' // Указываем тип содержимого
        },
        body: JSON.stringify({ "id": this.id }) // Преобразуем данные в JSON
      });

      if (!response.ok) {
        throw new Error('Network response was not ok ' + response.statusText);
      }

      const data = await response.text();
      alert(data);
    } catch (error) {
      console.error('There was a problem with the fetch operation:', error);
    }
  }

  getCard() {
    return this.id;
  }

  addToCart() {
    console.log(`Товар с ID ${this.id} добавлен в корзину.`);
  }

  removeFromCart() {
    console.log(`Товар с ID ${this.id} удален из корзины.`);
  }
}