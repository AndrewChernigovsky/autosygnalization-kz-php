export function editLinkPopular() {
  const link = document.querySelector(
    '.link.button.y-button-primary.popular__all-products'
  );
  const selectStat = JSON.parse(sessionStorage.getItem('selectStat'));
  const filtersState = JSON.parse(sessionStorage.getItem('filtersState'));
  if (link) {
    if (selectStat && filtersState) {
      const selectStatKeys = Object.keys(selectStat);
      const firstKey = selectStatKeys[0];
      const finalObject = selectStat[firstKey];
      let selectParams = '';
      let filtersParams = '';
      let rangeParams = '';
      let newUrl = '';

      for (const key in finalObject) {
        if (finalObject[key]?.checked === true) {
          selectParams = `?SELECT=${finalObject[key].value}&`;
        }
      }
      Object.entries(filtersState).forEach(([key, value]) => {
        if (value === true) {
          if (!filtersParams.includes(key)) {
            filtersParams += `${key}=on&`;
          }
        }
      });
      if (
        filtersState['min-value-cost'] &&
        !filtersParams.includes('min-value-cost')
      ) {
        rangeParams += `min-value-cost=${filtersState['min-value-cost']}&`;
      }
      if (
        filtersState['max-value-cost'] &&
        !filtersParams.includes('max-value-cost')
      ) {
        rangeParams += `max-value-cost=${filtersState['max-value-cost']}`;
      }
      newUrl = `${link.href}${selectParams}${filtersParams}${rangeParams}`;
      link.href = newUrl;
    } else {
      link.href = `${link.href}?SELECT=name&min-value-cost=100&max-value-cost=300000`;
    }
  }
}
