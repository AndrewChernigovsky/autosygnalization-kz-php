export default class ProcessForm {
  constructor(object) {
    if (!object) {
      console.log('FormOrder: неверно переданы параметры в конструктор');
      return;
    }

    this.form = document.querySelector(object.form);

    if(!this.form) return;
    this.inputModel = this.form.querySelector('input[name="model"]');
    this.inputReleaseYear = this.form.querySelector('input[name="release-year"]');
    this.inputName = this.form.querySelector('input[name="name"]');
    this.inputPhone = this.form.querySelector('input[name="phone"]');
    this.textarea = this.form.querySelector('textarea[name="message"]');


    this.sendObject = {};
    this.init();
  }

  init() {
    this.editSubmitEvent();
    if (this.inputPhone) {
      // Устанавливаем +7 по умолчанию
      this.inputPhone.value = '+7';
    }
    if (this.inputModel) {
      this.validModel(15);
    }
    if (this.inputReleaseYear) {
      this.validReleaseYear(4);
    }
    this.validName(60);
    this.validPhone(12);
    this.validMessage(100);
    
    // Добавляем обработчик на сброс формы
    if (this.form && this.inputPhone) {
      this.form.addEventListener('reset', () => {
        setTimeout(() => {
          this.inputPhone.value = '+7';
        }, 0);
      });
    }
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
    const url = "/server/php/process/process_form.php";

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
          this.form.reset();
          // После сброса формы восстанавливаем +7
          setTimeout(() => {
            if (this.inputPhone) {
              this.inputPhone.value = '+7';
            }
          }, 0);
        } else {
          alert('Ошибка при отправке заказа');
        }
      })
      .catch((error) => {
        console.log('Ошибка:', error);
        alert('Произошла ошибка при отправке данных');
      });
  }

  validModel(maxLength = 10) {
    const regExp = /[^a-zA-Z]/g;
    const setValidation = () => {
      this.inputModel.value = this.inputModel.value.replace(regExp, '').slice(0, maxLength);
    }

    this.inputModel.removeEventListener('input', setValidation);
    this.inputModel.addEventListener('input', setValidation);
  }

  validReleaseYear(maxLength = 4) {
    const regExp = /[^0-9]/g;
    const setValidation = () => {
      this.inputReleaseYear.value = this.inputReleaseYear.value.replace(regExp, '').slice(0, maxLength);

      if (this.inputReleaseYear.value.length > 1) {
        this.inputReleaseYear.value = this.inputReleaseYear.value.replace(/^0+/, '') || '0';
      }
    }

    this.inputReleaseYear.removeEventListener('input', setValidation);
    this.inputReleaseYear.addEventListener('input', setValidation);

  }

  validName(maxLength = 40) {
    const regExp = /[^a-zA-ZА-Яа-яЁё -]/g; // Разрешаем буквы, пробелы и дефис
    const setValidation = () => {
      let value = this.inputName.value.replace(regExp, '').slice(0, maxLength);

      // Убираем повторяющиеся пробелы (например, "Иван   Иванов" → "Иван Иванов")
      value = value.replace(/\s+/g, ' ');

      this.inputName.value = value;
    };

    this.inputName.removeEventListener('input', setValidation);
    this.inputName.addEventListener('input', setValidation);
  }

  validPhone(maxLength = 11) {
    // Удаляем старые слушатели, если они есть
    const handler = (event) => {
      let value = event.target.value;

      // Проверяем, начинается ли значение с +7
      if (!value.startsWith('+7')) {
        // Если начинается с +, но не с +7, заменяем первые два символа на +7
        if (value.startsWith('+')) {
          value = '+7' + value.substring(1).replace(/[^0-9]/g, '');
        } else {
          // В противном случае добавляем +7 в начало
          value = '+7' + value.replace(/[^0-9]/g, '');
        }
      } else {
        // Если начинается с +7, просто удаляем все кроме цифр после +7
        const prefix = '+7';
        const rest = value.substring(2).replace(/[^0-9]/g, '');
        value = prefix + rest;
      }

      // Ограничиваем длину (учитывая +7)
      value = value.slice(0, maxLength + 1); // +1 для символа +

      this.inputPhone.value = value;
    };

    // Обработчик фокуса для установки +7, если поле пустое
    const focusHandler = () => {
      if (!this.inputPhone.value) {
        this.inputPhone.value = '+7';
      }
    };

    this.inputPhone.removeEventListener('input', handler);
    this.inputPhone.addEventListener('input', handler);
    
    this.inputPhone.removeEventListener('focus', focusHandler);
    this.inputPhone.addEventListener('focus', focusHandler);
  }

  validMessage(maxLength = 100) {
    const setValidation = () => {
      let value = this.textarea.value.slice(0, maxLength);

      this.textarea.value = value;
    }

    this.textarea.removeEventListener('input', setValidation);
    this.textarea.addEventListener('input', setValidation);
  }
}
