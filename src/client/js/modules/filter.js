export function filterToggleMenu() {
  const filterBtn = document.getElementById('filter-btn');
  const filterCatalog = document.getElementById('filter-catalog');
  const filterBtnClose = document.getElementById('filter-btn-close');
  let flag = false;

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
}
export default class FiltersAction {
  constructor(block, path = '/catalog') {
    const mainSelector = document.querySelector(block.form);
    if (!mainSelector) return;
    this.path = path;
    this.form = document.querySelector(block.form);
    this.inputList = this.form.querySelectorAll('input');
    this.currentUrl = new URL(window.location.href);
    this.currentParams = new URLSearchParams(window.location.search);
    this.filterStates =
      JSON.parse(sessionStorage.getItem('filterStates')) || {};
    this.init();
  }

  init() {
    this.createSessionStorageObject();
    this.editSessionStorageObject();
    this.restoreFiltersState();
    this.correctSubmitEvent();
    this.correctResetEvent();
  }

  createSessionStorageObject() {
    if (!this.filterStates[this.path]) {
      this.filterStates[this.path] = {};
      this.inputList.forEach((element) => {
        if (element.type === 'checkbox') {
          this.filterStates[this.path][element.name] = element.checked;
        } else {
          this.filterStates[this.path][element.name] = element.value;
        }
      });
      sessionStorage.setItem('filterStates', JSON.stringify(this.filterStates));
    }
  }

  editSessionStorageObject() {
    this.inputList.forEach((element) => {
      if (element.type === 'checkbox') {
        element.addEventListener('click', () => {
          this.filterStates[this.path][element.name] = element.checked;
          sessionStorage.setItem(
            'filterStates',
            JSON.stringify(this.filterStates)
          );
        });

      } else if (
        element.name === 'min-range-cost' ||
        element.name === 'min-value-cost'
      ) {
        element.addEventListener('input', () => {
          this.filterStates[this.path]['min-range-cost'] = element.value;
          this.filterStates[this.path]['min-value-cost'] = element.value;
          sessionStorage.setItem(
            'filterStates',
            JSON.stringify(this.filterStates)
          );
        });
      } else if (
        element.name === 'max-range-cost' ||
        element.name === 'max-value-cost'
      ) {
        element.addEventListener('input', () => {
          this.filterStates[this.path]['max-range-cost'] = element.value;
          this.filterStates[this.path]['max-value-cost'] = element.value;
          sessionStorage.setItem(
            'filterStates',
            JSON.stringify(this.filterStates)
          );
        });
      }
    });
  }

  restoreFiltersState() {
    this.inputList.forEach((element) => {
      if (element.type === 'checkbox') {
        if (this.filterStates[this.path][element.name] !== undefined) {
          element.checked = this.filterStates[this.path][element.name];
        }
      } else if (
        element.name === 'min-range-cost' ||
        element.name === 'min-value-cost'
      ) {
        if (
          this.filterStates[this.path]['min-range-cost'] !== undefined &&
          this.filterStates[this.path]['min-value-cost'] !== undefined
        ) {
          element.value = this.filterStates[this.path]['min-range-cost'];
          element.value = this.filterStates[this.path]['min-value-cost'];
        }
      } else if (
        element.name === 'max-range-cost' ||
        element.name === 'max-value-cost'
      ) {
        if (
          this.filterStates[this.path]['max-range-cost'] !== undefined &&
          this.filterStates[this.path]['max-value-cost'] !== undefined
        ) {
          element.value = this.filterStates[this.path]['max-range-cost'];
          element.value = this.filterStates[this.path]['max-value-cost'];
        }
      }
    });
  }

  correctSubmitEvent() {
    this.form.addEventListener('submit', (event) => {
      event.preventDefault();

      const formData = new FormData(this.form);
      formData.delete('min-range-cost');
      formData.delete('max-range-cost');
      const params = new URLSearchParams(formData);

      const cleanUrl = this.currentUrl.href.split('?')[0];
      const selectValue = this.currentUrl.searchParams.get('SELECT');
      const selectType = this.currentUrl.searchParams.get('type');

      if (this.currentParams.has('SELECT')) {
        location.assign(`${cleanUrl}?SELECT=${selectValue}&type=${selectType}&${params}`);
      }
    });
  }

  correctResetEvent() {
    this.form.addEventListener('reset', () => {
      this.inputList.forEach((element) => {
        if (element.type === 'checkbox') {
          if (this.filterStates[this.path][element.name] !== undefined) {
            this.filterStates[this.path][element.name] = false;
            sessionStorage.setItem(
              'filterStates',
              JSON.stringify(this.filterStates)
            );
          }
        } else if (
          element.name === 'min-range-cost' ||
          element.name === 'min-value-cost'
        ) {
          if (
            this.filterStates[this.path]['min-range-cost'] !== undefined &&
            this.filterStates[this.path]['min-value-cost'] !== undefined
          ) {
            this.filterStates[this.path]['min-range-cost'] = '100';
            this.filterStates[this.path]['min-value-cost'] = '100';
            sessionStorage.setItem(
              'filterStates',
              JSON.stringify(this.filterStates)
            );
          }
        } else if (
          element.name === 'max-range-cost' ||
          element.name === 'max-value-cost'
        ) {
          if (
            this.filterStates[this.path]['max-range-cost'] !== undefined &&
            this.filterStates[this.path]['max-value-cost'] !== undefined
          ) {
            this.filterStates[this.path]['max-range-cost'] = '300000';
            this.filterStates[this.path]['max-value-cost'] = '300000';
            sessionStorage.setItem(
              'filterStates',
              JSON.stringify(this.filterStates)
            );
          }
        }
      });

      const selectStat = JSON.parse(sessionStorage.getItem('selectStat')) || {};
      for (let key in selectStat[this.path]) {
        if (key == 'name') {
          selectStat[this.path][key].checked = true;
        } else {
          selectStat[this.path][key].checked = false;
        }
        sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
      }
      const selectType = this.currentUrl.searchParams.get('type');
      location.assign(`${this.currentUrl.href.split('?')[0]}?SELECT=name&type=${selectType}`);
    });
  }
}
