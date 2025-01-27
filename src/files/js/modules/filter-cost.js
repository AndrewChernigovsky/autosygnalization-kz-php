export default class DoubleRangeSlider {
  constructor(selector, settings = {}) {
    const mainSelector = document.querySelector(selector);
    if (!mainSelector) return;

    this.minNumberInput = mainSelector.querySelector('.input-min');
    this.maxNumberInput = mainSelector.querySelector('.input-max');
    this.minRangeInput = mainSelector.querySelector('.range-min');
    this.maxRangeInput = mainSelector.querySelector('.range-max');
    this.rangeBar = mainSelector.querySelector('.range-progress');

    this.minValue = this.#getAttributeValue(
      this.minNumberInput,
      'min',
      settings.minValue,
      0
    );
    this.maxValue = this.#getAttributeValue(
      this.maxNumberInput,
      'max',
      settings.maxValue,
      100
    );
    this.step = this.#getAttributeValue(
      this.minNumberInput,
      'step',
      settings.step,
      1
    );
    this.gap = settings.gap || 0;

    this.#initializeInputs();
    this.#initRangeSlider();
  }

  #getAttributeValue(input, attr, settingValue, defaultValue) {
    const value = input.getAttribute(attr);
    return value !== null
      ? parseFloat(value)
      : settingValue !== undefined
      ? settingValue
      : defaultValue;
  }

  #initializeInputs() {
    const inputs = [
      this.minNumberInput,
      this.maxNumberInput,
      this.minRangeInput,
      this.maxRangeInput,
    ];

    inputs.forEach((input) => {
      if (input) {
        input.min = input.getAttribute('min') || this.minValue;
        input.max = input.getAttribute('max') || this.maxValue;
        input.step = input.getAttribute('step') || this.step;
        input.value =
          input.classList.contains('input-min') ||
          input.classList.contains('range-min')
            ? input.min || this.minValue
            : input.max || this.maxValue;
      }
    });
  }

  #initRangeSlider() {
    const inputs = [
      this.minNumberInput,
      this.maxNumberInput,
      this.minRangeInput,
      this.maxRangeInput,
    ];

    inputs.forEach((input) => {
      input.addEventListener('input', (evt) => {
        this.#updateRangeBar(evt.target);
      });
    });

    // Обработчик сброса формы
    const form = this.minNumberInput.closest('.filter-form'); // Находим форму
    console.log(form);
    form.addEventListener('reset', () => {
      setTimeout(() => {
        this.minNumberInput.value = this.minValue;
        this.maxNumberInput.value = this.maxValue;
        this.minRangeInput.value = this.minValue;
        this.maxRangeInput.value = this.maxValue;

        this.#updateProgress();
      }, 0);
    });
  }

  #updateRangeBar(input) {
    let min = parseInt(this.minNumberInput.value);
    let max = parseInt(this.maxNumberInput.value);

    if (this.#isNumberInput(input)) {
      if (max - min < this.gap) {
        input.classList.contains('input-min')
          ? (min = max - this.gap)
          : (max = min + this.gap);
        if (input.classList.contains('input-min')) {
          this.minNumberInput.value = min;
        } else {
          this.maxNumberInput.value = max;
        }
      }
      this.minRangeInput.value = min;
      this.maxRangeInput.value = max;
    } else if (this.#isRangeInput(input)) {
      let rangeMin = parseInt(this.minRangeInput.value);
      let rangeMax = parseInt(this.maxRangeInput.value);

      if (rangeMax - rangeMin < this.gap) {
        input.classList.contains('range-min')
          ? (rangeMin = rangeMax - this.gap)
          : (rangeMax = rangeMin + this.gap);
        if (input.classList.contains('range-min')) {
          this.minRangeInput.value = rangeMin;
        } else {
          this.maxRangeInput.value = rangeMax;
        }
      }
      this.minNumberInput.value = rangeMin;
      this.maxNumberInput.value = rangeMax;
    }

    this.#updateProgress();
  }

  #isNumberInput(input) {
    return input.classList.contains('number-input');
  }

  #isRangeInput(input) {
    return input.classList.contains('range-input');
  }

  #updateProgress() {
    if (!this.rangeBar) return;

    const min = parseInt(this.minRangeInput.value);
    const max = parseInt(this.maxRangeInput.value);

    console.log('Обновление прогресс-бара:', { min, max });

    const percentMin =
      ((min - parseInt(this.minRangeInput.min)) /
        (this.maxValue - parseInt(this.minRangeInput.min))) *
      100;
    const percentMax =
      ((max - parseInt(this.maxRangeInput.min)) /
        (this.maxValue - parseInt(this.maxRangeInput.min))) *
      100;

    this.rangeBar.style.left = `${percentMin}%`;
    this.rangeBar.style.right = `${100 - percentMax}%`;
  }
}
