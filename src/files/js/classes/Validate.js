export default class Validate {
  #isYearValid = false;
  #isModelValid = false;
  #isNameValid = false;
  #isPhoneValid = false;

  validateYear(value) {
    const date = new Date();
    const year = date.getFullYear();

    const isValid = Number(value) > 1900 && Number(value) <= year;

    this.#isYearValid = isValid;

    return {
      isValid: isValid,
      text: isValid ? "" : "Год неверный",
    };
  }

  validateModel(value) {
    const isValid = value.length > 0 && value.length <= 16;
    this.#isModelValid = isValid;
    return {
      isValid: isValid,
      text: isValid ? "" : "Введите модель",
    };
  }

  validateName(value) {
    const isValid = value.length >= 2 && value.length <= 40;
    this.#isNameValid = isValid;
    return {
      isValid: isValid,
      text: isValid ? "" : "Введите имя",
    };
  }

  validatePhone(value) {
    const regex = /^\+7 \(\d{3}\) \d{2} \d{2} \d{3}$/;

    const isValid = regex.test(value);
    this.#isPhoneValid = isValid;
    return {
      isValid: isValid,
      text: isValid ? "" : "Неверный формат телефона",
    };
  }

  onInputBlur(input, func, funcArg) {
    const isValid = func(funcArg);
    this.showError(isValid, input);
  }

  showError(isValid, input) {
    const span = document.createElement("span");

    if (input.nextElementSibling) {
      input.nextElementSibling.remove();
    }

    if (!isValid.isValid) {
      span.textContent = isValid.text;
      span.classList.add("error-validate");
      input.after(span);
      input.classList.add("error");
      input.classList.remove("success");
    } else {
      input.classList.remove("error");
      input.classList.add("success");
    }
  }

  getValidResults() {
    return [
      this.#isYearValid,
      this.#isModelValid,
      this.#isNameValid,
      this.#isPhoneValid,
    ];
  }
}
