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
    this.cost = this.receptacle.querySelector(object.cost);

    if (
      this.items.length <= 0 ||
      !this.list ||
      !this.form ||
      !this.receptacle
    ) {
      console.log(
        'FormOrder: один из параметров неверно передан в конструктор'
      );
      return;
    } else {
      console.log('FormOrder: все параметры переданы в конструктор');
    }

    this.sendObject = {};
    this.path =
      path || window.location.pathname.includes('/dist') ? '/dist' : '';
    this.init();
  }

  init() {
    this.editSubmitEvent();
  }

  editSubmitEvent = () => {
    this.form.addEventListener('submit', (event) => {
      event.preventDefault();

      const data = new FormData(this.form);
      this.sendObject = {};
      this.sendObject['form'] = {};
      this.sendObject['items'] = {};
      this.sendObject['cost'] = this.cost.textContent;

      data.forEach((value, key) => {
        this.sendObject['form'][key] = value;
      });

      this.items.forEach((item, index) => {
        const title =
          item.querySelector('#product-title')?.textContent?.trim() ||
          'Название товара нет в базе данных';
        const quantity =
          item.querySelector('#product-quantity')?.textContent?.trim() ||
          'Кол-во товара нет в базе данных';
        const price =
          item.querySelector('#product-price')?.textContent?.trim() ||
          'Цены товара нет в базе данных';

        this.sendObject['items'][index] = {
          title,
          quantity:
            quantity === null || isNaN(Number(quantity))
              ? quantity
              : Number(quantity),
          price: price === null || isNaN(Number(price)) ? price : Number(price),
        };
      });
      console.log(this.sendObject);

      this.sendDataToServer();
    });
  }

  sendDataToServer() {
    const url = `${this.path}/files/php/data/form_order.php`;
    // const url = '/src/files/php/data/form_order.php';

    if (!this.sendObject || Object.keys(this.sendObject).length === 0) {
      alert('Ошибка: данные заказа отсутствуют');
      return;
    }

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(this.sendObject),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(
            `Ошибка сервера: ${response.status} ${response.statusText}`
          );
        }
        return response.json();
      })
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

  destroy() {  
    if (this.form) {
      this.form.removeEventListener('submit', this.editSubmitEvent);
    }
    this.receptacle = null;
    this.form = null;
    this.list = null;
    this.items = null;
    this.sendObject = null;
    this.path = null;

  }
}
