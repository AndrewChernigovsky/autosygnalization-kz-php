import { setPopup } from './../helpers/popup.js'

const form = document.querySelector('form');
const hiddenInput = document.querySelector('#password-hash');
const recaptchaField1 = document.getElementById('RecaptchaField1');
const recaptchaField2 = document.getElementById('RecaptchaField2');
const formCost = 'cost';
const formSimple = 'form-simple';

let widgetId1;
let widgetId2;
let recaptchaResponse;

function captchaCallback() {
  if (recaptchaField1 && recaptchaField2) {
    widgetId1 = grecaptcha.render(recaptchaField1, {
      'sitekey': '6LcXjXMqAAAAAOk-ZcPIIdan-9-WnbxIYv4Gbaav',
      'callback': function (response) {
        recaptchaResponse = response;
      }
    });

    widgetId2 = grecaptcha.render(recaptchaField2, {
      'sitekey': '6LcXjXMqAAAAAOk-ZcPIIdan-9-WnbxIYv4Gbaav',
      'callback': function (response) {
        recaptchaResponse = response;
      }
    });
  } else {
    console.error("reCAPTCHA fields not found");
  }
};

function initFormSimple(form) {
  if (form) {
    const phone = form.querySelector("input[name='user-tel']");
    if (phone) {
      Inputmask({
        mask: '+7 (999) 999-99-99',
      }).mask(phone);
    }
  }
}

const forms = document.querySelectorAll('form');

if (forms) {
  forms.forEach(form => sendFormValidation(form.getAttribute('id')))
}


function sendFormValidation(formId) {
  const form = document.getElementById(formId);

  function validation() {
    let isCheckedMessanger = false;
    let isCheckedtypeOfSite = false;
    let isCheckedshere = false;
    let isCheckeddate = false;
    if (form) {
      const phone = form.querySelector("input[name='user-tel']");
      const messengers = form.querySelectorAll("input[name='social']");
      const typeOfSites = form.querySelectorAll("input[name='type-of-site']")
      const sheres = form.querySelectorAll("input[name='shere']")
      const dates = form.querySelectorAll("input[name='date']")
      const totalCost = form.querySelector("input[name='total-cost']")

      messengers.forEach(radio => {
        if (radio.checked) {
          isCheckedMessanger = true;
        }
      });

      if (formId === formCost) {
        typeOfSites.forEach(radio => {
          if (radio.checked) {
            isCheckedtypeOfSite = true;
          }
        });
        sheres.forEach(radio => {
          if (radio.checked) {
            isCheckedshere = true;
          }
        });
        dates.forEach(radio => {
          if (radio.checked) {
            isCheckeddate = true;
          }
        });


        return {
          phoneValid: phone.value.replace(/\D/g, '').length === 11,
          messengerValid: isCheckedMessanger,
          typeOfSiteValid: isCheckedtypeOfSite,
          shereValid: isCheckedshere,
          dateValid: isCheckeddate,
          totalCost: totalCost.value.length >= 5,
          recaptchaValid: !!recaptchaResponse,
          hiddenInputValid: hiddenInput.value.length === 0,

        };
      }
      if (formId === formSimple) {
        return {
          phoneValid: phone.value.replace(/\D/g, '').length === 11,
          messengerValid: isCheckedMessanger,
          recaptchaValid: !!recaptchaResponse,
          hiddenInputValid: hiddenInput.value.length === 0
        };
      }
    }
  }
  if (form) {

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      initFormSimple(form);
      const validationResult = validation();
      const formCondition = () => {
        if (formId === formCost) {
          return (
            validationResult.hiddenInputValid && validationResult.phoneValid && validationResult.messengerValid && validationResult.typeOfSiteValid && validationResult.dateValid && validationResult.totalCost
            && validationResult.shereValid
          )
        }
        if (formId === formSimple) {
          return (
            validationResult.hiddenInputValid && validationResult.phoneValid && validationResult.messengerValid
          )
        }
        return false;
      }

      if (formCondition()) {
        if (!validationResult.recaptchaValid) {
          setPopup()
          return;
        } else {
          const formData = new FormData(form);
          formData.append('g-recaptcha-response', recaptchaResponse);
          const action = formId === formCost ? './files/php/functions/mail/mail.php' : './files/php/functions/mail/mail-simple.php';
          console.log(action, 'action');
          grecaptcha.reset(widgetId1);
          grecaptcha.reset(widgetId2);
          recaptchaResponse = undefined;
          for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
          }
          fetch(action, {
            method: 'POST',
            body: formData
          })
            .then(response => {
              if (!response.ok) {
                throw new Error('Network response was not ok');
              }
              setPopup('Данные успешно отправлены!')
              grecaptcha.reset(widgetId1);
              grecaptcha.reset(widgetId2);
            })
            .catch(error => console.error('Ошибка:', error));
        }
      } else {
        if (!validationResult.phoneValid) {
          setPopup('Некорректный номер телефона');
        }
        if (!validationResult.messengerValid) {
          setPopup('Выберите мессенджер');
        }
        if (formId === formCost) {
          if (!validationResult.typeOfSiteValid) {
            setPopup('Выберите тип сайта');
          }
          if (!validationResult.shereValid) {
            setPopup('Выберите сферу');
          }
          if (!validationResult.dateValid) {
            setPopup('Выберите дату');
          }
          if (!validationResult.totalCost) {
            setPopup('Введите корректную сумму');
          }
        }
      }
    });
  }
}

export function initFormValidation() {
  if (form) {
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js?render=explicit';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);

    script.onload = function () {
      grecaptcha.ready(function () {
        document.querySelectorAll('.captcha-btn').forEach(btn => btn.addEventListener('click', captchaCallback))
      });
    };
  }
}

