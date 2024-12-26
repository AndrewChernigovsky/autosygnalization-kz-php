export function initializeRangeFilter(
  minRangeSelector,
  maxRangeSelector,
  minValueSelector,
  maxValueSelector
) {
  const minRange = document.querySelector(minRangeSelector);
  const maxRange = document.querySelector(maxRangeSelector);
  const minValue = document.getElementById(minValueSelector);
  const maxValue = document.getElementById(maxValueSelector);

  // Инициализация значений
  minValue.textContent = minRange.value;
  maxValue.textContent = maxRange.value;

  // Обработчик событий для ползунков
  minRange.addEventListener("input", function () {
    minValue.textContent = minRange.value;

    // Убедитесь, что минимальное значение не превышает максимальное
    if (parseInt(minRange.value) > parseInt(maxRange.value)) {
      maxRange.value = minRange.value; // Синхронизируем максимальное значение
      maxValue.textContent = maxRange.value; // Обновляем отображаемое значение
    }
  });

  maxRange.addEventListener("input", function () {
    maxValue.textContent = maxRange.value;

    // Убедитесь, что максимальное значение не меньше минимального
    if (parseInt(maxRange.value) < parseInt(minRange.value)) {
      minRange.value = maxRange.value; // Синхронизируем минимальное значение
      minValue.textContent = minRange.value; // Обновляем отображаемое значение
    }
  });
}


