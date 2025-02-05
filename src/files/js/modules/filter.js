import CustomSelect from './select';
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

  const storedFiltersState = sessionStorage.getItem('filtersState');
  const filtersState = storedFiltersState ? JSON.parse(storedFiltersState) : {};

  const restoreState = () => {
    checkboxList.forEach((checkbox) => {
      if (filtersState[checkbox.name] !== undefined) {
        checkbox.checked = filtersState[checkbox.name];
      }
    });

    if (rangeMinInput && rangeMaxInput) {
      if (filtersState['min-value-range'] !== undefined) {
        rangeMinInput.value = filtersState['min-value-range'];
      }
      if (filtersState['max-value-range'] !== undefined) {
        rangeMaxInput.value = filtersState['max-value-range'];
      }
    }

    if (minCostInput && maxCostInput) {
      if (filtersState['min-value-cost'] !== undefined) {
        minCostInput.value = filtersState['min-value-cost'];
      }
      if (filtersState['max-value-cost'] !== undefined) {
        maxCostInput.value = filtersState['max-value-cost'];
      }
    }
  };

  const saveState = () => {
    checkboxList.forEach((checkbox) => {
      filtersState[checkbox.name] = checkbox.checked;
    });

    if (rangeMinInput && rangeMaxInput) {
      filtersState['min-value-range'] = rangeMinInput.value;
      filtersState['max-value-range'] = rangeMaxInput.value;
    }

    if (minCostInput && maxCostInput) {
      filtersState['min-value-cost'] = minCostInput.value;
      filtersState['max-value-cost'] = maxCostInput.value;
    }

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

  restoreState();

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

  form.addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    const filtersState = JSON.parse(sessionStorage.getItem('filtersState'));
    const selectState = JSON.parse(sessionStorage.getItem('selectState'));

    const params = new URLSearchParams(formData);

    Object.entries(filtersState).forEach(([key, value]) => {
      if (value === true) {
        if (!params.has(key)) {
          params.append(key, 'on');
        }
      }
    });

    if (filtersState['min-value-cost'] && !params.has('min-value-cost')) {
      params.append('min-value-cost', filtersState['min-value-cost']);
    }
    if (filtersState['max-value-cost'] && !params.has('max-value-cost')) {
      params.append('max-value-cost', filtersState['max-value-cost']);
    }

    if (selectState?.value && !params.has('SELECT')) {
      params.append('SELECT', selectState.value);
    }

    window.location.href = this.action + '?' + params.toString();
  });

  form.addEventListener('reset', function (event) {
    const defaultUrl = window.location.href.split('?')[0];
    for (let key in filtersState) {
      filtersState[key] = false;
    }
    if (rangeMinInput && rangeMaxInput) {
      filtersState['min-value-range'] = 100;
      filtersState['max-value-range'] = 300000;
    }

    if (minCostInput && maxCostInput) {
      filtersState['min-value-cost'] = 100;
      filtersState['max-value-cost'] = 300000;
    }
    sessionStorage.setItem('filtersState', JSON.stringify(filtersState));

    const defaultSelectState = {
      value: 'name',
      text: 'Название',
    };
    sessionStorage.setItem('selectState', JSON.stringify(defaultSelectState));

    window.location.href = defaultUrl;
  });

  if (minCostInput && maxCostInput) {
    minCostInput.addEventListener('input', saveState);
    maxCostInput.addEventListener('input', saveState);
  }
}
