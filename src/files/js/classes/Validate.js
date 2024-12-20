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
      text: isValid ? "" : "Введите модель машины",
    };
  }

  validateName(value) {
    const isValid = value.length > 0 && value.length <= 40;
    this.#isNameValid = isValid;
    return {
      isValid: isValid,
      text: isValid ? "" : "Ошибка имени",
    };
  }

  validatePhone(value) {
    const regex =
      /^\+7[\s(]*[0-9]{3}[\s)]*[0-9]{3}[\s-]?[0-9]{2}[\s-]?[0-9]{2}$/;

    const isValid = regex.test(value);
    this.#isPhoneValid = isValid;
    return {
      isValid: isValid,
      text: isValid ? "" : "Неверный формат телефона",
    };
  }

  onInputBlur(input, func, funcArg) {
    const isValid = func(funcArg);
    const span = document.createElement("span");

    if (input.nextElementSibling) {
      input.nextElementSibling.remove();
    }

    if (!isValid.isValid) {
      span.textContent = isValid.text;
      span.style.background = "red";
      span.classList.add("error-validate");
      input.after(span);
      input.classList.add("error");
    } else {
      input.classList.remove("error");
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
