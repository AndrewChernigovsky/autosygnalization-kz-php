export default class ProcessForm {
  constructor(object, path) {
    if (!object) {
      console.log('FormOrder: неверно переданы параметры в конструктор');
      return;
    }

    this.form = document.querySelector(object.form);
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


      data.forEach((value, key) => {
        this.sendObject[key] = value;
      });

      this.sendDataToServer();


    });
  }

  sendDataToServer() {
    const url = `${this.path}/files/php/data/process_form.php`;
    
    console.log('Отправляемые данные:', this.sendObject); // Логирование данных
  
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
        console.log('Ошибка:', error);
        alert('Произошла ошибка при отправке данных');
      });
  }


}
