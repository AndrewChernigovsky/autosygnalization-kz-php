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
export default class FiltersAction {
  constructor(block, path = 'files/php/pages/catalog/catalog.php') {
    const mainSelector = document.querySelector(block.form);
    if (!mainSelector) return;
    this.path = path;
    this.form = document.querySelector(block.form);
    this.checkboxList = this.form.querySelectorAll(
      '.filter-basic__checkbox, .filter-functions__checkbox'
    );
    this.minCostInput = this.form.querySelector('.number-input.input-min');
    this.maxCostInput = this.form.querySelector('.number-input.input-max');
    this.rangeMinInput = this.form.querySelector('.range-input.range-min');
    this.rangeMaxInput = this.form.querySelector('.range-input.range-max');
    this.rangeProgress = this.form.querySelector(
      '.filter-cost .range-progress'
    );
    this.currentParams = new URLSearchParams(window.location.search);
    this.init();
  }

  init() {
    this.createSessionStorageObject();
    this.restoreState();
    this.checkboxList.forEach((checkbox) => {
      checkbox.addEventListener('change', () => this.saveState());
    });

    if (this.rangeMinInput && this.rangeMaxInput) {
      this.rangeMinInput.addEventListener('input', () => {
        this.updateRangeProgress();
        this.saveState();
      });

      this.rangeMaxInput.addEventListener('input', () => {
        this.updateRangeProgress();
        this.saveState();
      });
    }
    if (this.minCostInput && this.maxCostInput) {
      this.minCostInput.addEventListener('input', () => this.saveState());
      this.maxCostInput.addEventListener('input', () => this.saveState());
    }

    this.updateUrlFromFilterState();

    this.form.addEventListener('submit', (event) => {
      event.preventDefault();

      const formData = new FormData(this.form);
      const filtersState =
        JSON.parse(sessionStorage.getItem('filterStates')) || {};
      const selectStat = JSON.parse(sessionStorage.getItem('selectStat')) || {};

      const params = new URLSearchParams(formData);

      if (Object.keys(selectStat).length > 0) {
        const selectStatKeys = Object.keys(selectStat);
        const firstKey = selectStatKeys[0];
        const finalObject = selectStat[firstKey];
        for (const key in finalObject) {
          if (finalObject[key]?.checked === true && !params.has('SELECT')) {
            params.append('SELECT', finalObject[key].value);
          }
        }
      }

      if (Object.keys(filtersState).length > 0) {
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
      }

      window.location.href = `${
        window.location.href.split('?')[0]
      }?${params.toString()}`;
    });

    this.form.addEventListener('reset', () => {
      const filterStates =
        JSON.parse(sessionStorage.getItem('filterStates')) || {};
      const pathState = filterStates[this.path] || {};

      Object.keys(pathState).forEach((key) => {
        pathState[key] = false;
      });

      if (this.rangeMinInput && this.rangeMaxInput) {
        Object.assign(pathState, {
          'min-value-range': 100,
          'max-value-range': 300000,
        });
      }

      if (this.minCostInput && this.maxCostInput) {
        Object.assign(pathState, {
          'min-value-cost': 100,
          'max-value-cost': 300000,
        });
      }

      filterStates[this.path] = pathState;
      sessionStorage.setItem('filterStates', JSON.stringify(filterStates));

      const selectStat = JSON.parse(sessionStorage.getItem('selectStat')) || {};
      if (selectStat) {
        const selectStatKeys = Object.keys(selectStat);
        const firstKey = selectStatKeys[0];
        const finalObject = selectStat[firstKey];
        for (const key in finalObject) {
          finalObject[key].checked = finalObject[key].value === 'name';
        }
        sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
      }

      window.location.href = `${
        window.location.href.split('?')[0]
      }?SELECT=name`;
    });
  }

  createSessionStorageObject() {
    const filterStates =
      JSON.parse(sessionStorage.getItem('filterStates')) || {};
    if (!filterStates[this.path]) {
      filterStates[this.path] = {};
    }
    this.checkboxList.forEach((checkbox) => {
      if (!filterStates[this.path][checkbox.name]) {
        filterStates[this.path][checkbox.name] = false;
      }
    });
    if (
      !filterStates[this.path]['min-value-cost'] ||
      !filterStates[this.path]['max-value-cost']
    ) {
      filterStates[this.path]['min-value-cost'] = '100';
      filterStates[this.path]['max-value-cost'] = '300000';
    }
    if (
      !filterStates[this.path]['min-value-range'] ||
      !filterStates[this.path]['max-value-range']
    ) {
      filterStates[this.path]['min-value-range'] = '100';
      filterStates[this.path]['max-value-range'] = '300000';
    }
    sessionStorage.setItem('filterStates', JSON.stringify(filterStates));
  }

  restoreState() {
    const filterStates =
      JSON.parse(sessionStorage.getItem('filterStates')) || {};
    const filtersState = filterStates[this.path] || {};

    this.checkboxList.forEach((checkbox) => {
      if (filtersState[checkbox.name] !== undefined) {
        checkbox.checked = filtersState[checkbox.name];
      }
    });

    if (this.rangeMinInput && this.rangeMaxInput) {
      if (filtersState['min-value-range'] !== undefined) {
        this.rangeMinInput.value = filtersState['min-value-range'];
      }
      if (filtersState['max-value-range'] !== undefined) {
        this.rangeMaxInput.value = filtersState['max-value-range'];
      }
    }

    if (this.minCostInput && this.maxCostInput) {
      if (filtersState['min-value-cost'] !== undefined) {
        this.minCostInput.value = filtersState['min-value-cost'];
      }
      if (filtersState['max-value-cost'] !== undefined) {
        this.maxCostInput.value = filtersState['max-value-cost'];
      }
    }
  }

  saveState() {
    const filterStates =
      JSON.parse(sessionStorage.getItem('filterStates')) || {};

    if (!filterStates[this.path]) {
      filterStates[this.path] = {};
    }

    const filtersState = filterStates[this.path];

    this.checkboxList.forEach((checkbox) => {
      filtersState[checkbox.name] = checkbox.checked;
    });

    if (this.rangeMinInput && this.rangeMaxInput) {
      filtersState['min-value-range'] = this.rangeMinInput.value;
      filtersState['max-value-range'] = this.rangeMaxInput.value;
    }

    if (this.minCostInput && this.maxCostInput) {
      filtersState['min-value-cost'] = this.minCostInput.value;
      filtersState['max-value-cost'] = this.maxCostInput.value;
    }

    sessionStorage.setItem('filterStates', JSON.stringify(filterStates));
  }

  updateRangeProgress() {
    if (this.rangeProgress && this.rangeMinInput && this.rangeMaxInput) {
      const min = parseInt(this.rangeMinInput.min, 10);
      const max = parseInt(this.rangeMaxInput.max, 10);
      const rangeMin = parseInt(this.rangeMinInput.value, 10);
      const rangeMax = parseInt(this.rangeMaxInput.value, 10);

      const leftPercent = ((rangeMin - min) / (max - min)) * 100;
      const rightPercent = ((rangeMax - min) / (max - min)) * 100;

      this.rangeProgress.style.left = `${leftPercent}%`;
      this.rangeProgress.style.right = `${100 - rightPercent}%`;
    }
  }

  updateUrlFromFilterState() {
    const filterState =
      JSON.parse(sessionStorage.getItem('filterStates')) || {};
    const pathState = filterState[this.path] || {};

    if (Object.keys(pathState).length > 0) {
      const currentUrl = new URL(window.location.href);
      let isUpdated = false;

      Object.keys(pathState).forEach((key) => {
        if (pathState[key] === true) {
          if (currentUrl.searchParams.get(key) !== 'on') {
            currentUrl.searchParams.set(key, 'on');
            isUpdated = true;
          }
        } else {
          if (currentUrl.searchParams.has(key)) {
            currentUrl.searchParams.delete(key);
            isUpdated = true;
          }
        }
      });

      if (
        pathState['min-value-cost'] &&
        !currentUrl.searchParams.has('min-value-cost')
      ) {
        currentUrl.searchParams.set(
          'min-value-cost',
          pathState['min-value-cost']
        );
        isUpdated = true;
      }

      if (
        pathState['max-value-cost'] &&
        !currentUrl.searchParams.has('max-value-cost')
      ) {
        currentUrl.searchParams.set(
          'max-value-cost',
          pathState['max-value-cost']
        );
        isUpdated = true;
      }

      const currentSearchParams = currentUrl.searchParams.toString();
      const originalSearchParams = new URL(
        window.location.href
      ).searchParams.toString();

      if (isUpdated && currentSearchParams !== originalSearchParams) {
        window.location.assign(currentUrl);
      }
    }
  }
}
