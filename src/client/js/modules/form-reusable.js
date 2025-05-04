export default class FormReusable {
  constructor(object) {
    this.container = document.querySelector(object.container);
    this.form = this.container.querySelector(object.form);
    if(!this.form) return;
    this.inputName = this.form.querySelector('input[name="name"]');
    this.inputPhone = this.form.querySelector('input[name="phone"]');
    this.textarea = this.form.querySelector('textarea[name="message"]');
    this.sendObject = {};
    this.phoneButton = document.querySelector('#phone-button');
    this.phoneButtonWrapper = document.querySelector('.phone-button__wrapper');
    // Привязываем методы, чтобы this всегда указывал на экземпляр класса
    this.setValidationName = this.setValidationName.bind(this);
    this.setValidationPhone = this.setValidationPhone.bind(this);
    this.setValidationMessage = this.setValidationMessage.bind(this);
    this.onPhoneFieldFocus = this.onPhoneFieldFocus.bind(this);

    this.init();
  }

  init() {
    // Устанавливаем +7 по умолчанию при инициализации
    if (this.inputPhone) {
      this.inputPhone.value = '+7';
    }
    this.setWork();
    this.validation();
  }

  setWork() {
    this.form.addEventListener('submit', (e) => {
      e.preventDefault();
      const url = "/server/php/process/process_form_reusable.php";
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
            this.closeModal()
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
    
    setValidationName() {
      this.inputName.value = this.inputName.value.replace(/[^a-zA-ZА-Яа-яЁё -]/g, '').slice(0, 40);
    }
    
    setValidationPhone() {
      let value = this.inputPhone.value;
      
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
      this.inputPhone.value = value.slice(0, 13); // +7 и еще 11 цифр
    }

    setValidationMessage() {
      this.textarea.value = this.textarea.value.slice(0, 100);
    }
    
    closeModal() {
      if(this.phoneButton && this.phoneButtonWrapper.classList.contains('active')) {
        this.phoneButtonWrapper.classList.remove('active')
      }
      this.form.reset();
      // После сброса формы снова устанавливаем +7
      if (this.inputPhone) {
        setTimeout(() => {
          this.inputPhone.value = '+7';
        }, 0);
      }
      this.container.classList.remove('active');
    }

  onPhoneFieldFocus() {
    if (!this.inputPhone.value || this.inputPhone.value === '') {
      this.inputPhone.value = '+7';
    }
  }

  validation() {
    this.inputName.addEventListener('input', this.setValidationName);
    this.inputPhone.addEventListener('input', this.setValidationPhone);
    
    // Добавляем обработчик фокуса для поля телефона
    this.inputPhone.addEventListener('focus', this.onPhoneFieldFocus);
    
    // Добавляем обработчик на сброс формы
    this.form.addEventListener('reset', () => {
      setTimeout(() => {
        if (this.inputPhone) {
          this.inputPhone.value = '+7';
        }
      }, 0);
    });
    
    if (this.textarea) {
      this.textarea.addEventListener('input', this.setValidationMessage);
    }
  }
}


