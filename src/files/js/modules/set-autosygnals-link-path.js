export function setAutosygnalsLinkPath() {
  const mainConatiner = document.querySelector(
    '.autosygnals__list.list-style-none.swiper-wrapper'
  );
  const linksList = mainConatiner.querySelectorAll('.autosygnals__item-link');

  const selectStat = JSON.parse(sessionStorage.getItem('selectStat')) || {
    'files/php/pages/parking-systems/parking-systems.php': {
      name: { checked: true, value: 'name', text: 'Название' },
      price: { checked: false, value: 'price', text: 'Цена' },
    },
    'files/php/pages/catalog/catalog.php': {
      name: { checked: true, value: 'name', text: 'Название' },
      price: { checked: false, value: 'price', text: 'Цена' },
    },
  };

  if (!sessionStorage.getItem('selectStat')) {
    sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
  }

  const filtersState = JSON.parse(sessionStorage.getItem('filtersState')) || {
    autosetup: false,
    'block-engine-can': false,
    'bluetooth-smart': false,
    'control-before-start': false,
    'control-phone': false,
    'data-level-bensin': false,
    'free-monitoring': false,
    'legkoe-avto': false,
    'max-value-cost': '300000',
    'max-value-range': '300000',
    'min-value-cost': '100',
    'min-value-range': '100',
    'smart-diagnostic': false,
    vnedorojnik: false,
  };

  if (!sessionStorage.getItem('filtersState')) {
    sessionStorage.setItem('filtersState', JSON.stringify(filtersState));
  }

  if (linksList) {
    linksList.forEach((link) => {
      link.addEventListener('click', () => {
        const getParams = new URLSearchParams(link.search);
        if (getParams.has('SELECT')) {
          const selectValue = getParams.get('SELECT');
          const catalogPage = selectStat['files/php/pages/catalog/catalog.php'];
          if (catalogPage) {
            Object.keys(catalogPage).forEach((key) => {
              catalogPage[key].checked = false;
            });
            if (catalogPage[selectValue]) {
              catalogPage[selectValue].checked = true;
            }
            sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
          }
        }

        Object.keys(filtersState).forEach((key) => {
          if (typeof filtersState[key] === 'boolean') {
            filtersState[key] = false;
          } else if (
            typeof filtersState[key] === 'string' &&
            filtersState[key].match(/^\d+$/)
          ) {
            filtersState[key] = key.includes('min') ? '100' : '300000';
          }
        });

        getParams.forEach((value, key) => {
          if (filtersState.hasOwnProperty(key)) {
            if (value === 'on') {
              filtersState[key] = true;
            } else if (value.match(/^\d+$/)) {
              filtersState[key] = value;
            }
          }
        });

        sessionStorage.setItem('filtersState', JSON.stringify(filtersState));
      });
    });
  }
}
