export function filterToggleMenu() {
  const filterBtn = document.getElementById('filter-btn');
  const filterCatalog = document.getElementById('filter-catalog');
  const filterBtnClose = document.getElementById('filter-btn-close');
  let flag = false;

  function checkWindowSize() {
    const isMobile = window.innerWidth <= 1024;
    if (isMobile) {
      filterCatalog.classList.remove('open');
      filterBtnClose.style.display = 'none';
    }
  }

  function toggleFiltersMenu() {
    if (flag) {
      filterCatalog.classList.remove('open');
      filterBtnClose.style.display = 'none';
      flag = false;
    } else {
      filterCatalog.classList.add('open');
      filterBtnClose.style.display = 'flex';
      flag = true;
    }
  }

  filterBtn.addEventListener('click', () => {
    toggleFiltersMenu();
  });

  filterBtnClose.addEventListener('click', () => {
    toggleFiltersMenu();
  });
  checkWindowSize();
  window.addEventListener('resize', checkWindowSize);
}

export function saveCheckbox() {
  const form = document.querySelector('.filter-form');
  const checkboxList = document.querySelectorAll(
    '.filter-basic__checkbox, .filter-functions__checkbox'
  );

  const minCostInput = document.querySelector('.number-input.input-min');
  const maxCostInput = document.querySelector('.number-input.input-max');
  const rangeMinInput = document.querySelector('.range-input.range-min');
  const rangeMaxInput = document.querySelector('.range-input.range-max');

  const rangeProgress = document.querySelector('.filter-cost .range-progress');

  // Восстановление состояния из sessionStorage
  const storedFiltersState = sessionStorage.getItem('filtersState');
  const filtersState = storedFiltersState ? JSON.parse(storedFiltersState) : {};

  const restoreState = () => {
    // Восстанавливаем состояния чекбоксов
    checkboxList.forEach((checkbox) => {
      if (filtersState[checkbox.name] !== undefined) {
        checkbox.checked = filtersState[checkbox.name];
      }
    });

    // Восстанавливаем состояния диапазонов
    if (rangeMinInput && rangeMaxInput) {
      if (filtersState['min-value-range'] !== undefined) {
        rangeMinInput.value = filtersState['min-value-range'];
      }
      if (filtersState['max-value-range'] !== undefined) {
        rangeMaxInput.value = filtersState['max-value-range'];
      }
    }

    // Восстанавливаем значения минимальной и максимальной стоимости
    if (minCostInput && maxCostInput) {
      if (filtersState['min-value-cost'] !== undefined) {
        minCostInput.value = filtersState['min-value-cost'];
      }
      if (filtersState['max-value-cost'] !== undefined) {
        maxCostInput.value = filtersState['max-value-cost'];
      }
    }

    // Обновляем прогресс диапазона
    updateRangeProgress();
  };

  const saveState = () => {
    // Сохраняем состояния чекбоксов
    checkboxList.forEach((checkbox) => {
      filtersState[checkbox.name] = checkbox.checked;
    });

    // Сохраняем значения диапазонов
    if (rangeMinInput && rangeMaxInput) {
      filtersState['min-value-range'] = rangeMinInput.value;
      filtersState['max-value-range'] = rangeMaxInput.value;
    }

    // Сохраняем значения минимальной и максимальной стоимости
    if (minCostInput && maxCostInput) {
      filtersState['min-value-cost'] = minCostInput.value;
      filtersState['max-value-cost'] = maxCostInput.value;
    }

    // Сохраняем в sessionStorage
    sessionStorage.setItem('filtersState', JSON.stringify(filtersState));
  };

  const updateRangeProgress = () => {
    if (rangeProgress && rangeMinInput && rangeMaxInput) {
      const min = parseInt(rangeMinInput.min, 10);
      const max = parseInt(rangeMaxInput.max, 10);
      const rangeMin = parseInt(rangeMinInput.value, 10);
      const rangeMax = parseInt(rangeMaxInput.value, 10);

      const leftPercent = ((rangeMin - min) / (max - min)) * 100;
      const rightPercent = ((rangeMax - min) / (max - min)) * 100;

      rangeProgress.style.left = `${leftPercent}%`;
      rangeProgress.style.right = `${100 - rightPercent}%`;
    }
  };

  // Восстанавливаем состояния при загрузке
  restoreState();

  // Добавляем обработчики событий
  checkboxList.forEach((checkbox) => {
    checkbox.addEventListener('change', saveState);
  });

  if (rangeMinInput && rangeMaxInput) {
    rangeMinInput.addEventListener('input', () => {
      updateRangeProgress();
      saveState();
    });

    rangeMaxInput.addEventListener('input', () => {
      updateRangeProgress();
      saveState();
    });
  }

  form.addEventListener('reset', function (event) {
    if (rangeMinInput && rangeMaxInput) {
      filtersState['min-value-range'] = 100;
      filtersState['max-value-range'] = 300;
    }

    if (minCostInput && maxCostInput) {
      filtersState['min-value-cost'] = 100;
      filtersState['max-value-cost'] = 300;
    }
  });

  if (minCostInput && maxCostInput) {
    minCostInput.addEventListener('input', saveState);
    maxCostInput.addEventListener('input', saveState);
  }
}
