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

    this.init();
  }

  init() {
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
      let value = this.inputPhone.value.replace(/[^0-9]/g, '');
      if (this.inputPhone.value.startsWith('+')) {
        value = '+' + value;
      }
      this.inputPhone.value = value.slice(0, 12);
    }

    setValidationMessage() {
      this.textarea.value = this.textarea.value.slice(0, 100);
    }
    
    closeModal() {
      if(this.phoneButton && this.phoneButtonWrapper.classList.contains('active')) {
        this.phoneButtonWrapper.classList.remove('active')
      }
      this.form.reset();
    this.container.classList.remove('active');
    
  }

  validation() {
    this.inputName.addEventListener('input', this.setValidationName);
    this.inputPhone.addEventListener('input', this.setValidationPhone);
    if (this.textarea) {
      this.textarea.addEventListener('input', this.setValidationMessage);
    }
  }
}


