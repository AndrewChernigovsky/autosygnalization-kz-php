export function editLinkParking() {
  const link = document.getElementById(
    'link__dist_files_php_pages_parking-systems_parking-systems.php'
  );

  // Получаем selectStat из sessionStorage или устанавливаем значения по умолчанию
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

  // Устанавливаем значения в sessionStorage, если они отсутствуют
  if (!sessionStorage.getItem('selectStat')) {
    sessionStorage.setItem('selectStat', JSON.stringify(selectStat));
  }

  const parkingRangeState = JSON.parse(
    sessionStorage.getItem('parkingRangeState')
  ) || {
    'max-value-cost': 300000,
    'max-value-range': 300000,
    'min-value-cost': 100,
    'min-value-range': 100,
  };

  if (!sessionStorage.getItem('parkingRangeState')) {
    sessionStorage.setItem(
      'parkingRangeState',
      JSON.stringify(parkingRangeState)
    );
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
    vnedorojnik: true,
  };

  if (!sessionStorage.getItem('filtersState')) {
    sessionStorage.setItem('filtersState', JSON.stringify(filtersState));
  }

  if (link) {
    const firstKey = Object.keys(selectStat)[0];
    const finalObject = selectStat[firstKey];
    let selectParams = '';
    let rangeParams = '';
    let filterParams = '';

    for (const key in finalObject) {
      if (finalObject[key]?.checked === true) {
        selectParams = `?SELECT=${finalObject[key].value}&`;
      }
    }

    if (parkingRangeState['min-value-cost']) {
      rangeParams += `min-value-cost=${parkingRangeState['min-value-cost']}&`;
    }
    if (parkingRangeState['max-value-cost']) {
      rangeParams += `max-value-cost=${parkingRangeState['max-value-cost']}&`;
    }

    const newUrl =
      `${link.href}${selectParams}${rangeParams}${filterParams}`.replace(
        /&$/,
        ''
      );
    link.href = newUrl;
  }
}
