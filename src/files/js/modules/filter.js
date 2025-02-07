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
  const mainContainer = document.querySelector(
    '.catalog__wrapper.all-products'
  );
  if (mainContainer) {
    const form = mainContainer.querySelector('.filter-form');
    const checkboxList = mainContainer.querySelectorAll(
      '.filter-basic__checkbox, .filter-functions__checkbox'
    );

    const minCostInput = mainContainer.querySelector('.number-input.input-min');
    const maxCostInput = mainContainer.querySelector('.number-input.input-max');
    const rangeMinInput = mainContainer.querySelector('.range-input.range-min');
    const rangeMaxInput = mainContainer.querySelector('.range-input.range-max');
    const rangeProgress = mainContainer.querySelector(
      '.filter-cost .range-progress'
    );

    const storedFiltersState = sessionStorage.getItem('filtersState');
    const filtersState = storedFiltersState
      ? JSON.parse(storedFiltersState)
      : {};
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
      const selectStat = JSON.parse(sessionStorage.getItem('selectStat'));

      const params = new URLSearchParams(formData);
      if (selectStat) {
        const selectStatKeys = Object.keys(selectStat);
        const firstKey = selectStatKeys[0];
        const finalObject = selectStat[firstKey];
        for (const key in finalObject) {
          if (finalObject[key]?.checked === true && !params.has('SELECT')) {
            alert(finalObject[key].value);
            params.append('SELECT', finalObject[key].value);
          }
        }
      }

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

      window.location.href = this.action + '?' + params.toString();
    });

    form.addEventListener('reset', function () {
      Object.keys(filtersState).forEach((key) => (filtersState[key] = false));

      if (rangeMinInput && rangeMaxInput) {
        Object.assign(filtersState, {
          'min-value-range': 100,
          'max-value-range': 300000,
        });
      }

      if (minCostInput && maxCostInput) {
        Object.assign(filtersState, {
          'min-value-cost': 100,
          'max-value-cost': 300000,
        });
      }

      sessionStorage.setItem('filtersState', JSON.stringify(filtersState));

      // Устанавливаем состояние сортировки и сразу извлекаем value
      const selectStat = JSON.parse(sessionStorage.getItem('selectStat'));
      if (selectStat) {
        const selectStatKeys = Object.keys(selectStat);
        const firstKey = selectStatKeys[0];
        const finalObject = selectStat[firstKey];
        for (const key in finalObject) {
          if (finalObject[key].value === 'name') {
            finalObject[key].checked = true;
          } else {
            finalObject[key].checked = false;
          }
        }
        sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
      }

      window.location.href = `${
        window.location.href.split('?')[0]
      }?SELECT=name`;
    });

    if (minCostInput && maxCostInput) {
      minCostInput.addEventListener('input', saveState);
      maxCostInput.addEventListener('input', saveState);
    }

    function updateUrlFromFilterState() {
      const filterState = JSON.parse(sessionStorage.getItem('filtersState'));

      if (filterState) {
        const currentUrl = new URL(window.location.href);
        let isUpdated = false;

        Object.keys(filterState).forEach((key) => {
          if (filterState[key] === true) {
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
          filterState['min-value-cost'] &&
          !currentUrl.searchParams.has('min-value-cost')
        ) {
          currentUrl.searchParams.set(
            'min-value-cost',
            filterState['min-value-cost']
          );
          isUpdated = true;
        }
        if (
          filterState['max-value-cost'] &&
          !currentUrl.searchParams.has('max-value-cost')
        ) {
          currentUrl.searchParams.set(
            'max-value-cost',
            filterState['max-value-cost']
          );
          isUpdated = true;
        }

        const currentSearchParams = currentUrl.searchParams.toString();
        const originalSearchParams = new URL(
          window.location.href
        ).searchParams.toString();

        if (isUpdated && currentSearchParams !== originalSearchParams) {
          window.history.pushState({}, '', currentUrl);
          location.reload();
        }
      }
    }

    updateUrlFromFilterState();
  }
}

export function saveRangeInParking() {
  const parkingContainer = document.querySelector(
    '.catalog__wrapper.parking-system'
  );
  if (parkingContainer) {
    const form = parkingContainer.querySelector('.filter-form');

    const minCostInput = parkingContainer.querySelector(
      '.number-input.input-min'
    );
    const maxCostInput = parkingContainer.querySelector(
      '.number-input.input-max'
    );
    const rangeMinInput = parkingContainer.querySelector(
      '.range-input.range-min'
    );
    const rangeMaxInput = parkingContainer.querySelector(
      '.range-input.range-max'
    );
    const rangeProgress = parkingContainer.querySelector(
      '.filter-cost .range-progress'
    );

    const parkingRangeState =
      JSON.parse(sessionStorage.getItem('parkingRangeState')) || {};
    const restoreState = () => {
      if (rangeMinInput && rangeMaxInput) {
        if (parkingRangeState['min-value-range'] !== undefined) {
          rangeMinInput.value = parkingRangeState['min-value-range'];
        }
        if (parkingRangeState['max-value-range'] !== undefined) {
          rangeMaxInput.value = parkingRangeState['max-value-range'];
        }
      }

      if (minCostInput && maxCostInput) {
        if (parkingRangeState['min-value-cost'] !== undefined) {
          minCostInput.value = parkingRangeState['min-value-cost'];
        }
        if (parkingRangeState['max-value-cost'] !== undefined) {
          maxCostInput.value = parkingRangeState['max-value-cost'];
        }
      }
    };

    const saveState = () => {
      if (rangeMinInput && rangeMaxInput) {
        parkingRangeState['min-value-range'] = rangeMinInput.value;
        parkingRangeState['max-value-range'] = rangeMaxInput.value;
      }

      if (minCostInput && maxCostInput) {
        parkingRangeState['min-value-cost'] = minCostInput.value;
        parkingRangeState['max-value-cost'] = maxCostInput.value;
      }

      sessionStorage.setItem(
        'parkingRangeState',
        JSON.stringify(parkingRangeState)
      );
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

    const rangeWork = () => {
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
    };

    const formEditSubmit = () => {
      form.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        const parkingRangeState = JSON.parse(
          sessionStorage.getItem('parkingRangeState')
        );
        const selectStat = JSON.parse(sessionStorage.getItem('selectStat'));
        const params = new URLSearchParams(formData);

        if (selectStat) {
          const selectStatKeys = Object.keys(selectStat);
          const needKey = selectStatKeys[1];
          const finalObject = selectStat[needKey];
          for (const key in finalObject) {
            if (finalObject[key]?.checked === true && !params.has('SELECT')) {
              alert(finalObject[key].value);
              params.append('SELECT', finalObject[key].value);
            }
          }
        }

        if (
          parkingRangeState['min-value-cost'] &&
          !params.has('min-value-cost')
        ) {
          params.append('min-value-cost', parkingRangeState['min-value-cost']);
        }
        if (
          parkingRangeState['max-value-cost'] &&
          !params.has('max-value-cost')
        ) {
          params.append('max-value-cost', parkingRangeState['max-value-cost']);
        }

        window.location.href = this.action + '?' + params.toString();
      });
    };

    const formEditReset = () => {
      form.addEventListener('reset', function () {
        if (rangeMinInput && rangeMaxInput) {
          Object.assign(parkingRangeState, {
            'min-value-range': 100,
            'max-value-range': 300000,
          });
        }

        if (minCostInput && maxCostInput) {
          Object.assign(parkingRangeState, {
            'min-value-cost': 100,
            'max-value-cost': 300000,
          });
        }

        sessionStorage.setItem(
          'parkingRangeState',
          JSON.stringify(parkingRangeState)
        );

        const selectStat = JSON.parse(sessionStorage.getItem('selectStat'));
        if (selectStat) {
          const selectStatKeys = Object.keys(selectStat);
          const needKey = selectStatKeys[0];
          const finalObject = selectStat[needKey];
          for (const key in finalObject) {
            if (finalObject[key].value === 'name') {
              finalObject[key].checked = true;
            } else {
              finalObject[key].checked = false;
            }
          }
          sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
        }

        window.location.href = `${
          window.location.href.split('?')[0]
        }?SELECT=name`;
      });
    };

    const updateUrlFromFilterState = () => {
      const parkingRangeState = JSON.parse(
        sessionStorage.getItem('fparkingRangeState')
      );

      if (parkingRangeState) {
        const currentUrl = new URL(window.location.href);
        let isUpdated = false;

        if (
          parkingRangeState['min-value-cost'] &&
          !currentUrl.searchParams.has('min-value-cost')
        ) {
          currentUrl.searchParams.set(
            'min-value-cost',
            parkingRangeState['min-value-cost']
          );
          isUpdated = true;
        }
        if (
          parkingRangeState['max-value-cost'] &&
          !currentUrl.searchParams.has('max-value-cost')
        ) {
          currentUrl.searchParams.set(
            'max-value-cost',
            parkingRangeState['max-value-cost']
          );
          isUpdated = true;
        }

        const currentSearchParams = currentUrl.searchParams.toString();
        const originalSearchParams = new URL(
          window.location.href
        ).searchParams.toString();

        if (isUpdated && currentSearchParams !== originalSearchParams) {
          window.history.pushState({}, '', currentUrl);
          location.reload();
        }
      }
    };
    restoreState();
    rangeWork();
    if (minCostInput && maxCostInput) {
      minCostInput.addEventListener('input', saveState);
      maxCostInput.addEventListener('input', saveState);
    }
    formEditSubmit();
    formEditReset();
    updateUrlFromFilterState();
  }
}
