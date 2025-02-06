export default class CustomSelect {
  constructor(block, path = 'files/php/pages/catalog/catalog.php') {
    const mainSelector = document.querySelector(block.selected);
    if (!mainSelector) return;
    this.path = path;
    this.selected = document.querySelector(block.selected);
    this.item = document.querySelector(block.item);
    this.options = this.item.querySelectorAll(block.options);
    this.value = null;
    this.currentParams = new URLSearchParams(window.location.search);
    this.init();
  }

  init() {
    this.createSessionStorageObject();
    this.checkForFirstStartParamPresence();
    this.overwriteExistingParams();
    this.addEventListeners();
  }

  createSessionStorageObject() {
    const slectStat = JSON.parse(sessionStorage.getItem('slectStat')) || {};
    if (!slectStat[this.path]) {
      slectStat[this.path] = {};
    }
    this.options.forEach((key) => {
      const optionValue = key.dataset.value;
      if (!slectStat[this.path][optionValue]) {
        slectStat[this.path][optionValue] = {
          checked: false,
          value: optionValue,
          text: key.innerHTML,
        };
      }
    });
    sessionStorage.setItem('slectStat', JSON.stringify(slectStat));
  }

  checkForFirstStartParamPresence() {
    if (this.currentParams.has('SELECT')) return;

    const slectStat = JSON.parse(sessionStorage.getItem('slectStat')) || {};
    const keys = slectStat[this.path];

    if (!keys) return;

    const hasChecked = Object.values(keys).some((item) => item.checked);

    if (!hasChecked) {
      const firstKey = Object.keys(keys)[0];

      if (firstKey) {
        this.value = keys[firstKey].value;
        this.selected.innerHTML = keys[firstKey].text;
        this.selected.dataset.value = this.value;
        keys[firstKey].checked = true;

        this.updateUrl(this.value);
      }
    }

    sessionStorage.setItem('slectStat', JSON.stringify(slectStat));
  }

  overwriteExistingParams() {
    const slectStat = JSON.parse(sessionStorage.getItem('slectStat'));
    const paramState = this.currentParams.get('SELECT');
    const keys = slectStat[this.path];
    for (let key in keys) {
      if (keys[key].checked === true) {
        this.value = keys[key].value;
        this.selected.innerHTML = keys[key].text;
        this.selected.dataset.value = this.value;
        if (this.value !== paramState) {
          this.updateUrl(this.value);
        }
      }
    }
  }

  saveSelectedState() {
    const selectedValue = this.selected.dataset.value;
    const slectStat = JSON.parse(sessionStorage.getItem('slectStat'));
    const keys = slectStat[this.path];
    for (let key in keys) {
      if (keys[key].value === selectedValue) {
        keys[key].checked = true;
      } else {
        keys[key].checked = false;
      }
    }

    sessionStorage.setItem('slectStat', JSON.stringify(slectStat));
  }

  updateUrl(value) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('SELECT', value);
    window.history.pushState({}, '', currentUrl);
    location.reload();
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
      if (this.value !== this.currentParams.get('SELECT')) {
        this.updateUrl(this.value);
      }
    }
  }

  getValue() {
    return this.value;
  }
}
