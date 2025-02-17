export default class FormOrder {
  constructor(object, path) {
    if (!object) {
      console.log('FormOrder: неверно переданы параметры в конструктор');
      return;
    }

    this.receptacle = document.querySelector(object.receptacle);
    this.form = this.receptacle.querySelector(object.form);
    this.list = this.receptacle.querySelector(object.list);
    this.items = this.list.querySelectorAll(object.items);

    if (!this.receptacle || !this.form || !this.list || !this.items) {
      console.log(
        'FormOrder: один из параметров неверно передан в конструктор'
      );
      return;
    } else {
      console.log('FormOrder: все параметры переданы в конструктор');
    }

    this.sendObject = {};
    this.path = path || window.location.pathname;
    this.init();
  }

  init() {
    this.editSubmitEvent();
  }

  editSubmitEvent() {
    this.form.addEventListener('submit', (event) => {
      event.preventDefault();

      const data = new FormData(this.form);
      this.sendObject = {};
      this.sendObject['form'] = {};
      this.sendObject['items'] = {};

      data.forEach((value, key) => {
        this.sendObject['form'][key] = value;
      });

      this.items.forEach((item, index) => {
        const title =
          item.querySelector('#product-title')?.textContent.trim() || '';
        const quantity =
          item.querySelector('#product-quantity')?.textContent.trim() || '';
        const price =
          item.querySelector('#product-price')?.textContent.trim() || '';

        this.sendObject['items'][index] = {
          title,
          quantity: Number(quantity),
          price: Number(price),
        };
      });
      console.log(this.sendObject);

      this.sendDataToServer();
    });
  }

  sendDataToServer() {
    const url = '/src/files/php/data/form_order.php';

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(this.sendObject),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log('Ответ от сервера:', data);
        if (data.success) {
          alert('Заказ успешно отправлен!');
        } else {
          alert('Ошибка при отправке заказа');
        }
      })
      .catch((error) => {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при отправке данных');
      });
  }
}
