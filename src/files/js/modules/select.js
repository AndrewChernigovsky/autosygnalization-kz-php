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
    const urlParams = new URLSearchParams(window.location.search);
    const currentState = urlParams.get('SELECT');
    const selectState = JSON.parse(sessionStorage.getItem('selectState'));

    if (selectState && selectState.value) {
      this.value = selectState.value;
      this.selected.innerHTML = selectState.text;
      this.selected.dataset.value = this.value;
      if (this.value !== currentState) {
        this.sendStateToPHP(this.value);
        this.updateUrl(this.value);
        this.reloadPage();
      }
    } else {
      if (this.options.length) {
        const firstOption = this.options[0];
        this.selected.innerHTML = firstOption.innerHTML;
        this.value = firstOption.dataset.value;
        this.selected.dataset.value = this.value;
        this.saveSelectedState();
        this.sendStateToPHP(this.value);
        if (this.value !== currentState) {
          this.updateUrl(this.value);
          this.reloadPage();
        }
      }
    }

    this.addEventListeners();
  }

  saveSelectedState() {
    const selectedValue = this.selected.dataset.value;
    const selectState = {
      value: selectedValue,
      text: this.selected.innerHTML,
    };

    sessionStorage.setItem('selectState', JSON.stringify(selectState));
  }

  sendStateToPHP(value) {
    fetch('/files/php/helpers/set_state.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ state: value }),
    });
  }

  updateUrl(value) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('SELECT', value);
    window.history.pushState({}, '', currentUrl);
  }

  reloadPage() {
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
      if (
        this.value !== new URLSearchParams(window.location.search).get('SELECT')
      ) {
        this.sendStateToPHP(this.value);
        this.updateUrl(this.value);
        this.reloadPage();
      }
    }
  }

  getValue() {
    return this.value;
  }
}
