const feedbackForm = document.querySelector('#feedback-form');
const numberInput = feedbackForm.querySelector('#release-year');
const nameInput = feedbackForm.querySelector('#name');
const phoneInput = feedbackForm.querySelector('#phone');
const submitButton = feedbackForm.querySelector('.form__button');
let recaptchaResponse;
let widgetId1;

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
    const scriptExist = document.querySelector('head script[src="https://www.google.com/recaptcha/api.js"]');
    if (!scriptExist) {
      const script = document.createElement('script');
      script.src = 'https://www.google.com/recaptcha/api.js';
      script.async = true;
      script.defer = true;
      document.head.appendChild(script);

      script.onload = function () {
        if (typeof grecaptcha !== 'undefined') {
          grecaptcha.ready(function () {
            captchaCallback()
          });
        }
      };
    } else {
      captchaCallback();
    }
  }

  function captchaCallback() {
    const recaptchaField1 = document.getElementById('recaptcha-field1');
    if (recaptchaField1 && !widgetId1) {
      widgetId1 = grecaptcha.render(recaptchaField1, {
        'sitekey': '6LcXjXMqAAAAAOk-ZcPIIdan-9-WnbxIYv4Gbaav',
        'callback': function (response) {
          recaptchaResponse = response;
        }
      })
    } else if (widgetId1) {
      console.warn("reCAPTCHA has already been rendered in this element.");
    } else {
      console.error("reCAPTCHA fields not found");
    }
  };
}

if (feedbackForm != null) {
  const captchaRender = feedbackForm.querySelector('#captcha-render');
  if (captchaRender) {
    captchaRender.addEventListener('click', initFormValidation);
  } else {
    console.log("Captcha render element not found");
  }
}
