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

export function saveChecbox() {
  const urlParams = new URLSearchParams(window.location.search);

  document
    .querySelectorAll('.filter-basic__checkbox, .filter-functions__checkbox')
    .forEach((checkbox) => {
      if (urlParams.has(checkbox.name)) {
        checkbox.checked = true;
      }
    });

  const minCostInput = document.querySelector('.number-input.input-min');
  const maxCostInput = document.querySelector('.number-input.input-max');
  const rangeMinInput = document.querySelector('.range-input.range-min');
  const rangeMaxInput = document.querySelector('.range-input.range-max');

  document
    .querySelectorAll('.filter-basic__checkbox, .filter-functions__checkbox')
    .forEach((checkbox) => {
      checkbox.addEventListener('change', () => {
        sessionStorage.setItem(checkbox.name, checkbox.checked);
      });
    });

  if (minCostInput && maxCostInput && rangeMinInput && rangeMaxInput) {
    rangeMinInput.addEventListener('input', () => {
      sessionStorage.setItem('min-value-cost', rangeMinInput.value);
    });

    rangeMaxInput.addEventListener('input', () => {
      sessionStorage.setItem('max-value-cost', rangeMaxInput.value);
    });
  }

  function updateRangeProgress(rangeMinInput, rangeMaxInput) {
    const rangeProgress = document.querySelector(
      '.filter-cost .range-progress'
    );
    const min = parseInt(rangeMinInput.min, 10);
    const max = parseInt(rangeMaxInput.max, 10);
    const rangeMin = parseInt(rangeMinInput.value, 10);
    const rangeMax = parseInt(rangeMaxInput.value, 10);

    if (rangeProgress) {
      const leftPercent = ((rangeMin - min) / (max - min)) * 100;
      const rightPercent = ((rangeMax - min) / (max - min)) * 100;

      rangeProgress.style.left = `${leftPercent}%`;
      rangeProgress.style.right = `${100 - rightPercent}%`;
    }
  }

  if (minCostInput && maxCostInput && rangeMinInput && rangeMaxInput) {
    const minCost = sessionStorage.getItem('min-value-cost');
    const maxCost = sessionStorage.getItem('max-value-cost');

    if (minCost !== null) {
      minCostInput.value = minCost;
      rangeMinInput.value = minCost;
      updateRangeProgress(rangeMinInput, rangeMaxInput);
    }

    if (maxCost !== null) {
      maxCostInput.value = maxCost;
      rangeMaxInput.value = maxCost;
      updateRangeProgress(rangeMinInput, rangeMaxInput);
    }
  }

  document
    .querySelectorAll('.filter-basic__checkbox, .filter-functions__checkbox')
    .forEach((checkbox) => {
      const storedChecked = sessionStorage.getItem(checkbox.name);
      if (storedChecked === 'true') {
        checkbox.checked = true;
      }
    });
}
