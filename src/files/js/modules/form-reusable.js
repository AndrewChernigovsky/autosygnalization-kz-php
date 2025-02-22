export default class FormReusable {
  constructor(object, path) {
    this.container = document.querySelector(object.container);
    this.form = this.container.querySelector(object.form);
    this.path =
    path || window.location.pathname.includes('/dist') ? '/dist' : '';
    this.sendObject = {};
    this.init();
  }

  init() {
    this.setWork();
  }

  setWork() {
    this.form.addEventListener('submit', (e) => {
      e.preventDefault();
      const url = `${this.path}/files/php/data/form_reusable.php`;
      const formData = new FormData(this.form);
      formData.forEach((value, key) => {
        this.sendObject[key] = value;
      });
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
    });
  }
}


