export default class FormOrder {
  constructor(object) {
    if (!object) {
      console.log('FormOrder: неверно переданы параметры в конструктор');
      return;
    }

    this.receptacle = document.querySelector(object.receptacle);
    this.form = this.receptacle.querySelector(object.form);
    this.list = this.receptacle.querySelector(object.list);
    this.items = this.list.querySelectorAll(object.items);
    this.cost = this.receptacle.querySelector(object.cost);
    this.abortController = new AbortController();

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
    this.path = window.location.pathname.includes('/dist') ? '/dist' : '';
    this.init();
  }

  init() {
    this.editSubmitEvent();
    this.validation();
  }

  editSubmitEvent = () => {
    this.form.addEventListener('submit', (event) => {
      event.preventDefault();

      const data = new FormData(this.form);
      this.sendObject = {
        form: {},
        items: {},
        cost: this.cost.textContent,
      };

      data.forEach((value, key) => {
        this.sendObject.form[key] = value;
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

        this.sendObject.items[index] = {
          title,
          quantity: isNaN(Number(quantity)) ? quantity : Number(quantity),
          price: isNaN(Number(price)) ? price : Number(price),
        };
      });

      console.log(this.sendObject);

      this.sendDataToServer()
        .then(() => {
          this.destroy();
          location.reload();
        })
        .catch((error) => {
          console.error('Ошибка при отправке данных:', error);
        });

    }, { signal: this.abortController.signal });
  };

  sendDataToServer() {
    const url = `${this.path}/files/php/data/form_order.php`;

    if (!this.sendObject || Object.keys(this.sendObject).length === 0) {
      alert('Ошибка: данные заказа отсутствуют');
      return Promise.reject('Нет данных для отправки');
    }

    return fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(this.sendObject),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Ошибка сервера: ${response.status} ${response.statusText}`);
        }
        return response.json();
      })
      .then((data) => {
        console.log('Ответ от сервера:', data);
        if (data.success) {
          alert('Заказ успешно отправлен!');
        } else {
          alert('Ошибка при отправке заказа');
          return Promise.reject('Ошибка на сервере');
        }
      });
  }

  validation() {
    const inputClientTypeCheckbox = this.form.querySelectorAll('input[name="client_type"]');

    // Объект для хранения ссылок на обработчики
    this.inputHandlers = {};

    const getClientType = (e) => {
      const clientType = e
        ? e.target.value
        : this.form.querySelector('input[name="client_type"]:checked')?.value;

      // Удаляем старые обработчики перед добавлением новых
      Object.entries(this.inputHandlers).forEach(([key, handler]) => {
        const input = this.form.querySelector(`input[name="${key}"]`);
        if (input) input.removeEventListener('input', handler);
      });

      this.inputHandlers = {}; // Очищаем объект с обработчиками

      const addInputHandler = (name, regex, maxLength) => {
        const input = this.form.querySelector(`input[name="${name}"]`);
        if (!input) return;

        const handler = (e) => {
          e.target.value = e.target.value.replace(regex, '').slice(0, maxLength);
        };

        input.addEventListener('input', handler);
        this.inputHandlers[name] = handler;
      };

      // Общие инпуты
      addInputHandler('city', /[^а-яА-ЯёЁa-zA-Z\s-]/g, 40);
      addInputHandler('street', /[^а-яА-ЯёЁa-zA-Z\s-]/g, 50);
      addInputHandler('index', /[^0-9]/g, 6);
      addInputHandler('house', /[^0-9]/g, 3);
      addInputHandler('corpus', /[^0-9]/g, 3);
      addInputHandler('apartment', /[^0-9]/g, 3);
      addInputHandler('user-name', /[^а-яА-ЯёЁa-zA-Z\s-]/g, 20);
      addInputHandler('user-lastname', /[^а-яА-ЯёЁa-zA-Z\s-]/g, 30);
      addInputHandler('telephone', /[^0-9+\-() ]/g, 20);
      addInputHandler('email', /[^a-zA-Z0-9@._%+-]/g, 200);

      // Если выбрано "Юридическое лицо"
      if (clientType === 'Юридическое лицо') {
        addInputHandler('company-name', /[^а-яА-ЯёЁa-zA-Z\s-]/g, 40);
        addInputHandler('company-adress', /[^а-яА-ЯёЁa-zA-Z\s-]/g, 80);
        addInputHandler('company-index', /[^0-9]/g, 6);
        addInputHandler('INN', /[^0-9]/g, 10);
        addInputHandler('KPP', /[^0-9]/g, 9);
        addInputHandler('OGRN', /[^0-9]/g, 13);
        addInputHandler('BIK', /[^0-9]/g, 9);
        addInputHandler('cash-number', /[^0-9]/g, 20);
        addInputHandler('company-telephone', /[^0-9+\-() ]/g, 20);
      }
    };

    // Навешиваем обработчики на переключение типа клиента
    inputClientTypeCheckbox.forEach(input => {
      input.addEventListener('change', getClientType);
    });

    getClientType(); // Запускаем сразу при загрузке
  }



  destroy() {
    this.abortController.abort();
    this.receptacle = null;
    this.form = null;
    this.list = null;
    this.items = null;
    this.sendObject = null;
    this.path = null;

  }
}
