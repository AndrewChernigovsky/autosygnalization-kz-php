const feedbackForm = document.querySelector('#feedback-form');
const numberInput = feedbackForm.querySelector('#release-year');
const nameInput = feedbackForm.querySelector('#name');
const phoneInput = feedbackForm.querySelector('#phone');
const submitButton = feedbackForm.querySelector('.form__button');

export function validateSectionForm() {
  submitButton.addEventListener('click', (e) => {
    e.preventDefault();
    validateForm();
  });

  numberInput.addEventListener('input', (e) => {
    const inputValue = e.target.value;
    const formattedValue = inputValue.replace(/^[A-Za-zА-Яа-яЁё\s]+$/, '');
    e.target.value = formattedValue;
  });

  nameInput.addEventListener('input', (e) => {
    const inputValue = e.target.value;
    const formattedValue = inputValue.replace(/[0-9]/g, '');
    e.target.value = formattedValue;
  });

  phoneInput.addEventListener('input', (e) => {
    const inputValue = e.target.value;
    if (inputValue.startsWith('+')) {
      const formattedValue = inputValue.replace(/[^0-9+]/g, '').substr(0, 12);
      e.target.value = formattedValue;
    } else {
      const formattedValue = inputValue.replace(/^8/, '').replace(/[^0-9]/g, '');
      e.target.value = `+7${formattedValue.substr(0, 10)}`;
    }
  });
}

function validateForm() {
  let isValid = true;

  if (!nameInput.value.match(/^[а-яА-Яa-zA-Z\s]+$/)) {
    nameInput.setCustomValidity('Ввод цифр недопустим');
    isValid = false;
    nameInput.classList.add('error');
    nameInput.reportValidity();
  } else {
    nameInput.setCustomValidity('');
    nameInput.classList.remove('error');
  }

  if (!phoneInput.value.match(/^\+7[0-9]+$/)) {
    phoneInput.setCustomValidity('Введите телефон в формате +7XXXXXXXXXX');
    isValid = false;
    phoneInput.classList.add('error');
    phoneInput.reportValidity();
  } else {
    phoneInput.setCustomValidity('');
    phoneInput.classList.remove('error');
  }

  if (isValid) {
    grecaptcha.execute();
  }
}

function onReCaptchaSuccess(token) {
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = 'g-recaptcha-response';
    tokenInput.value = token;

    feedbackForm.appendChild(tokenInput);
    feedbackForm.submit();
}

window.onReCaptchaSuccess = onReCaptchaSuccess;

function initFormValidation() {
  if (feedbackForm) {
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);

    script.onload = function () {
    grecaptcha.ready(function () {
    submitButton.addEventListener('click', (e) => {
      e.preventDefault();
      validateForm();
    });
  });
};
  }
}

initFormValidation();