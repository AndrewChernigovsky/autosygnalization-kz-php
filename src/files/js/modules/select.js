// export default class CustomSelect {
//   constructor(block, path = 'files/php/pages/catalog/catalog.php') {
//     const mainSelector = document.querySelector(block.selected);
//     if (!mainSelector) return;
//     this.path = path;
//     this.selected = document.querySelector(block.selected);
//     this.item = document.querySelector(block.item);
//     this.options = this.item.querySelectorAll(block.options);
//     this.value = null;
//     this.currentParams = new URLSearchParams(window.location.search);
//     this.init();
//   }

//   init() {
//     this.createSessionStorageObject();
//     this.checkForFirstStartParamPresence();
//     this.overwriteExistingParams();
//     this.addEventListeners();
//   }

//   createSessionStorageObject() {
//     const selectStat = JSON.parse(sessionStorage.getItem('selectStat')) || {};
//     if (!selectStat[this.path]) {
//       selectStat[this.path] = {};
//     }
//     this.options.forEach((key) => {
//       const optionValue = key.dataset.value;
//       if (!selectStat[this.path][optionValue]) {
//         selectStat[this.path][optionValue] = {
//           checked: false,
//           value: optionValue,
//           text: key.innerHTML,
//         };
//       }
//     });
//     sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
//   }

//   checkForFirstStartParamPresence() {
//     if (this.currentParams.has('SELECT')) return;

//     const selectStat = JSON.parse(sessionStorage.getItem('selectStat')) || {};
//     const keys = selectStat[this.path];

//     if (!keys) return;

//     const hasChecked = Object.values(keys).some((item) => item.checked);
//     if (!hasChecked) {
//       const firstKey = Object.keys(keys)[0];
//       if (firstKey) {
//         this.value = keys[firstKey].value;
//         this.selected.innerHTML = keys[firstKey].text;
//         this.selected.dataset.value = this.value;
//         keys[firstKey].checked = true;
//         this.updateUrl(this.value);
//       }
//     }

//     sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
//   }

//   overwriteExistingParams() {
//     const paramState = this.currentParams.get('SELECT');
//     const selectStat = JSON.parse(sessionStorage.getItem('selectStat'));
//     const keys = selectStat[this.path];
//     for (let key in keys) {
//       if (keys[key].checked === true) {
//         this.value = keys[key].value;
//         this.selected.innerHTML = keys[key].text;
//         this.selected.dataset.value = this.value;
//         if (this.value !== paramState) {
//           this.updateUrl(this.value);
//         }
//       }
//     }
//   }

//   saveSelectedState() {
//     const selectedValue = this.selected.dataset.value;
//     const selectStat = JSON.parse(sessionStorage.getItem('selectStat'));
//     const keys = selectStat[this.path];
//     for (let key in keys) {
//       if (keys[key].value === selectedValue) {
//         keys[key].checked = true;
//       } else {
//         keys[key].checked = false;
//       }
//     }

//     sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
//   }

//   updateUrl(value) {
//     const currentUrl = new URL(window.location.href);
//     currentUrl.searchParams.set('SELECT', value);
//     window.history.pushState({}, '', currentUrl);
//     location.reload();
//   }

//   addEventListeners() {
//     this.selected.addEventListener(
//       'click',
//       this.handleSelectedClick.bind(this)
//     );
//     this.item.addEventListener('click', this.handleItemClick.bind(this));
//   }

//   handleSelectedClick() {
//     this.item.classList.toggle('select-hide');
//     this.selected.classList.toggle('open');
//     const currentRect = this.selected.getBoundingClientRect();
//     const currentWidth = currentRect.width;
//     this.options.forEach(
//       (option) => (option.style.width = `${currentWidth}px`)
//     );

//     document.addEventListener('click', this.handleClickOutside.bind(this));
//   }

//   handleClickOutside(e) {
//     if (!this.selected.contains(e.target) && !this.item.contains(e.target)) {
//       this.item.classList.add('select-hide');
//       this.selected.classList.remove('open');
//       document.removeEventListener('click', this.handleClickOutside.bind(this));
//     }
//   }

//   handleItemClick(e) {
//     if (e.target.closest('.select-item')) {
//       this.selected.innerHTML = e.target.innerHTML;
//       this.item.classList.add('select-hide');
//       this.selected.classList.remove('open');
//       this.value = e.target.dataset.value;
//       this.selected.dataset.value = this.value;
//       this.saveSelectedState();
//       if (this.value !== this.currentParams.get('SELECT')) {
//         this.updateUrl(this.value);
//       }
//     }
//   }

//   getValue() {
//     return this.value;
//   }
// }

// export default class CustomSelect {
//   constructor(block, path = 'files/php/pages/catalog/catalog.php') {
//     const mainSelector = document.querySelector(block.selected);
//     if (!mainSelector) return;
//     this.path = path;
//     this.selected = document.querySelector(block.selected);
//     this.item = document.querySelector(block.item);
//     this.options = this.item.querySelectorAll(block.options);
//     this.value = null;
//     this.currentParams = new URLSearchParams(window.location.search);
//     this.paramState = this.currentParams.get('SELECT');
//     this.init();
//   }

//   init() {
//     this.createSessionStorageObject();
//     this.addEventListeners();
//   }

//   createSessionStorageObject() {
//     const selectStat = JSON.parse(sessionStorage.getItem('selectStat')) || {};
//     if (!selectStat[this.path]) {
//       selectStat[this.path] = {};
//       this.options.forEach((key, index) => {
//         const optionValue = key.dataset.value;
//         if (!selectStat[this.path][optionValue]) {
//           selectStat[this.path][optionValue] = {
//             checked: false,
//             value: optionValue,
//             text: key.textContent,
//           };
//           if (index === 0) {
//             selectStat[this.path][optionValue].checked = true;
//             this.selected.dataset.value =
//               selectStat[this.path][optionValue].value;
//             this.selected.textContent = selectStat[this.path][optionValue].text;
//           }
//         }
//       });
//       sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
//       this.updateUrl();
//     } else {
//       const keys = selectStat[this.path];
//       for (let key in keys) {
//         if (keys[key].checked === true) {
//           this.value = keys[key].value;
//           this.selected.innerHTML = keys[key].text;
//           this.selected.dataset.value = this.value;
//           this.updateUrl(this.value);
//         }
//       }
//     }
//   }

//   updateUrl() {
//     this.value = this.selected.dataset.value;
//     if (this.value !== this.paramState) {
//       this.currentParams.set('SELECT', this.value);
//       window.location.assign(`${this.path}?${this.currentParams.toString()}`);
//     }
//   }

//   saveSelectedState() {
//     const selectStat = JSON.parse(sessionStorage.getItem('selectStat')) || {};
//     for (let key in selectStat[this.path]) {
//       if (selectStat[this.path][key].value === this.selected.dataset.value) {
//         selectStat[this.path][key].checked = true;
//       } else {
//         selectStat[this.path][key].checked = false;
//       }
//     }
//     sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
//   }

//   addEventListeners() {
//     this.selected.addEventListener(
//       'click',
//       this.handleSelectedClick.bind(this)
//     );
//     this.item.addEventListener('click', this.handleItemClick.bind(this));
//   }

//   handleSelectedClick() {
//     this.item.classList.toggle('select-hide');
//     this.selected.classList.toggle('open');
//     const currentRect = this.selected.getBoundingClientRect();
//     const currentWidth = currentRect.width;
//     this.options.forEach(
//       (option) => (option.style.width = `${currentWidth}px`)
//     );

//     document.addEventListener('click', this.handleClickOutside.bind(this));
//   }

//   handleClickOutside(e) {
//     if (!this.selected.contains(e.target) && !this.item.contains(e.target)) {
//       this.item.classList.add('select-hide');
//       this.selected.classList.remove('open');
//       document.removeEventListener('click', this.handleClickOutside.bind(this));
//     }
//   }

//   handleItemClick(e) {
//     if (e.target.closest('.select-item')) {
//       this.selected.innerHTML = e.target.innerHTML;
//       this.item.classList.add('select-hide');
//       this.selected.classList.remove('open');
//       this.value = e.target.dataset.value;
//       this.selected.dataset.value = this.value;
//       this.saveSelectedState();
//       if (this.value !== this.currentParams.get('SELECT')) {
//         this.updateUrl();
//       }
//     }
//   }

//   getValue() {
//     return this.value;
//   }
// }

export default class CustomSelect {
  constructor(block, path = 'files/php/pages/catalog/catalog.php') {
    if (!block) {
      console.error('CustomSelect: неверно переданы параметры в конструктор');
      return;
    }

    this.selected = document.querySelector(block.selected);
    this.item = document.querySelector(block.item);
    this.options = this.item.querySelectorAll(block.options);

    if (!this.selected || !this.item || !this.options.length) {
      console.error('CustomSelect: неверно переданы параметры в конструктор');
      return;
    }

    try {
      this.selectStat = JSON.parse(sessionStorage.getItem('selectStat')) || {};
    } catch (e) {
      console.warn('CustomSelect: ошибка парсинга sessionStorage', e);
      this.selectStat = {};
    }

    this.path = path || window.location.pathname;
    this.currentParams = new URLSearchParams(window.location.search);
    this.currentUrl = new URL(window.location.href);
    this.paramState = this.currentParams.get('SELECT');
    this.value = null;
    this.init();
  }

  init() {
    this.createSessionStorageObject();
    this.addEventListeners();
    this.updateUrl();
  }

  createSessionStorageObject() {
    if (!this.selectStat[this.path]) {
      this.selectStat[this.path] = {};
      this.options.forEach((option, index) => {
        if (!this.selectStat[this.path][option.dataset.value]) {
          this.selectStat[this.path][option.dataset.value] = {
            checked: false,
            value: option.dataset.value,
            text: option.textContent,
          };
          if (index === 0) {
            this.selectStat[this.path][option.dataset.value].checked = true;
            this.selected.dataset.value =
              this.selectStat[this.path][option.dataset.value].value;
            this.selected.textContent =
              this.selectStat[this.path][option.dataset.value].text;
            this.value = this.selected.dataset.value;
          }
        }
      });

      try {
        sessionStorage.setItem('selectStat', JSON.stringify(this.selectStat));
      } catch (e) {
        console.warn('CustomSelect: ошибка при сохранении в sessionStorage', e);
      }
    } else {
      for (let option in this.selectStat[this.path]) {
        if (this.selectStat[this.path][option].checked === true) {
          this.value = this.selectStat[this.path][option].value;
          this.selected.innerHTML = this.selectStat[this.path][option].text;
          this.selected.dataset.value = this.value;
          break;
        }
      }
    }
  }

  saveSelectedState() {
    for (let option in this.selectStat[this.path]) {
      if (
        this.selectStat[this.path][option].value === this.selected.dataset.value
      ) {
        this.selectStat[this.path][option].checked = true;
      } else {
        this.selectStat[this.path][option].checked = false;
      }
    }
    try {
      sessionStorage.setItem('selectStat', JSON.stringify(this.selectStat));
    } catch (e) {
      console.warn('CustomSelect: ошибка при сохранении в sessionStorage', e);
    }
  }

  updateUrl() {
    if (this.paramState !== this.value && this.value !== null) {
      if (!this.currentUrl.searchParams.has('SELECT')) {
        this.currentParams.set('SELECT', this.value);

        for (let [key, value] of this.currentUrl.searchParams.entries()) {
          this.currentParams.set(key, value);
        }

        this.currentUrl.search = this.currentParams.toString();
      } else {
        this.currentUrl.searchParams.set('SELECT', this.value);
      }

      alert('я отработал');
      location.assign(this.currentUrl.toString());
    }
  }

  addEventListeners() {
    this.selected.addEventListener('click', this.handleSelectedClick);
    this.item.addEventListener('click', this.handleItemClick);
  }

  handleSelectedClick = () => {
    this.item.classList.toggle('select-hide');
    this.selected.classList.toggle('open');
    const currentRect = this.selected.getBoundingClientRect();
    const currentWidth = currentRect.width;
    this.options.forEach(
      (option) => (option.style.width = `${currentWidth}px`)
    );

    document.addEventListener('click', this.handleClickOutside);
  };

  handleItemClick = (e) => {
    if (e.target.closest('.select-item')) {
      this.selected.innerHTML = e.target.innerHTML;
      this.item.classList.add('select-hide');
      this.selected.classList.remove('open');
      this.value = e.target.dataset.value;
      this.selected.dataset.value = this.value;
      this.saveSelectedState();
      this.updateUrl();
    }
  };

  handleClickOutside = (e) => {
    if (!this.selected.contains(e.target) && !this.item.contains(e.target)) {
      this.item.classList.add('select-hide');
      this.selected.classList.remove('open');
      document.removeEventListener('click', this.handleClickOutside);
    }
  };
}
