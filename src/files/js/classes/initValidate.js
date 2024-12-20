import Validate from "./Validate.js";
const yearInput = document.getElementById("release-year");
const modelInput = document.getElementById("model");
const nameInput = document.getElementById("name");
const phoneInput = document.getElementById("phone");
const feedbackForm = document.getElementById("feedback-form");

const validate = new Validate();

export function initValidate() {
  yearInput.addEventListener("blur", () => {
    validate.onInputBlur(
      yearInput,
      validate.validateYear.bind(validate),
      yearInput.value
    );
  });
  modelInput.addEventListener("blur", () => {
    validate.onInputBlur(
      modelInput,
      validate.validateModel.bind(validate),
      modelInput.value
    );
  });
  nameInput.addEventListener("blur", () => {
    validate.onInputBlur(
      nameInput,
      validate.validateName.bind(validate),
      nameInput.value
    );
  });
  phoneInput.addEventListener("blur", () => {
    validate.onInputBlur(
      phoneInput,
      validate.validatePhone.bind(validate),
      phoneInput.value
    );
  });

  feedbackForm.addEventListener("change", () => {
    const formBtn = feedbackForm.querySelector(".form__button");

    validate.validateYear(yearInput.value);
    validate.validateModel(modelInput.value);
    validate.validateName(nameInput.value);
    validate.validatePhone(phoneInput.value);

    const isFormValid = validate
      .getValidResults()
      .every((elem) => elem === true);

    formBtn.classList.toggle("disabled", !isFormValid);
    formBtn.disabled = !isFormValid;
  });
}
