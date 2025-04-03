export default class CustomSelect {
  constructor(block, path = '/catalog') {
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
