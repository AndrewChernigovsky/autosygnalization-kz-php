export default class CustomSelect {
  path;

  constructor(block, path = 'files/php/pages/catalog/catalog.php') {
    const mainSelector = document.querySelector(block.selected);
    if (!mainSelector) return;
    this.selected = document.querySelector(block.selected);
    this.item = document.querySelector(block.item);
    this.options = this.item.querySelectorAll(block.options);
    this.value = null;
    this.PRODUCTION = window.location.href.includes('/dist/') ? '/dist/' : '/';
    this.path = path;
    this.init();
  }

  init() {
    if (!sessionStorage.getItem('defaultSettings')) {
      this.setDefaultSelect();
      sessionStorage.setItem('defaultSettings', 'true');
    }

    this.loadSelectedState();

    this.addEventListeners();
    console.log(this.PRODUCTION);
    console.log(this.path);
  }

  setDefaultSelect() {
    if (this.options && this.options.length) {
      for (let element of this.options) {
        if (element.classList.contains('default')) {
          this.selected.innerHTML = element.innerHTML;
          this.value = element.dataset.value;
          this.selected.dataset.value = this.value;
          this.getValue();
          break;
        }
      }
    }
  }

  saveSelectedState() {
    const selectedValue = this.selected.dataset.value;
    const selectState = {
      value: selectedValue,
      text: this.selected.innerHTML,
    };

    sessionStorage.setItem('selectState', JSON.stringify(selectState));
  }

  loadSelectedState() {
    const storedState = sessionStorage.getItem('selectState');
    if (storedState) {
      const selectState = JSON.parse(storedState);
      this.selected.innerHTML = selectState.text;
      this.selected.dataset.value = selectState.value;
      this.value = selectState.value;
    } else {
      this.setDefaultSelect();
    }
  }

  addSelectToUrl() {
    const currentUrl = window.location.href.split('?')[0];
    const currentParams = new URLSearchParams(window.location.search);
    currentParams.set('SELECT', this.value);
    const newUrl = `${currentUrl}?${currentParams.toString()}`;

    document.location.href = newUrl;
  }

  addEventListeners() {
    this.selected.addEventListener(
      'click',
      this.handleSelectedClick.bind(this)
    );
    this.item.addEventListener('click', this.handleItemClick.bind(this));
  }

  handleSelectedClick() {
    this.item.classList.toggle('select-hide');
    this.selected.classList.toggle('open');
    const currentRect = this.selected.getBoundingClientRect();
    const currentWidth = currentRect.width;
    this.options.forEach(
      (option) => (option.style.width = `${currentWidth}px`)
    );

    document.addEventListener('click', this.handleClickOutside.bind(this));
  }

  handleClickOutside(e) {
    if (!this.selected.contains(e.target) && !this.item.contains(e.target)) {
      this.item.classList.add('select-hide');
      this.selected.classList.remove('open');
      document.removeEventListener('click', this.handleClickOutside.bind(this));
    }
  }

  handleItemClick(e) {
    if (e.target.closest('.select-item')) {
      this.selected.innerHTML = e.target.innerHTML;
      this.item.classList.add('select-hide');
      this.selected.classList.remove('open');
      this.value = e.target.dataset.value;
      this.selected.dataset.value = this.value;
      this.saveSelectedState();
      this.addSelectToUrl();
    }
  }

  getValue() {
    return this.value;
  }
}
