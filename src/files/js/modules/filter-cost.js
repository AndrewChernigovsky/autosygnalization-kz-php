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

  minValue.textContent = minRange.value;
  maxValue.textContent = maxRange.value;

  minRange.addEventListener("input", function () {
    minValue.textContent = minRange.value;

    if (parseInt(minRange.value) > parseInt(maxRange.value)) {
      maxRange.value = minRange.value;
      maxValue.textContent = maxRange.value;
    }
  });

  maxRange.addEventListener("input", function () {
    maxValue.textContent = maxRange.value;

    if (parseInt(maxRange.value) < parseInt(minRange.value)) {
      minRange.value = maxRange.value;
      minValue.textContent = minRange.value;
    }
  });
}


