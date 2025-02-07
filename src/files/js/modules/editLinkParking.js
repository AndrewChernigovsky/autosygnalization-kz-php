export function editLinkParking() {
  const link = document.getElementById(
    'link__dist_files_php_pages_parking-systems_parking-systems.php'
  );
  const selectStat = JSON.parse(sessionStorage.getItem('selectStat'));
  const parkingRangeState = JSON.parse(
    sessionStorage.getItem('parkingRangeState')
  );
  if (link.href) {
    if (selectStat && parkingRangeState) {
      const selectStatKeys = Object.keys(selectStat);
      const firstKey = selectStatKeys[0];
      const finalObject = selectStat[firstKey];
      let selectParams = '';
      let rangeParams = '';
      let newUrl = '';

      for (const key in finalObject) {
        if (finalObject[key]?.checked === true) {
          selectParams = `?SELECT=${finalObject[key].value}&`;
        }
      }
      if (
        parkingRangeState['min-value-cost'] &&
        !rangeParams.includes('min-value-cost')
      ) {
        rangeParams += `min-value-cost=${parkingRangeState['min-value-cost']}&`;
      }
      if (
        parkingRangeState['max-value-cost'] &&
        !rangeParams.includes('max-value-cost')
      ) {
        rangeParams += `max-value-cost=${parkingRangeState['max-value-cost']}`;
      }
      newUrl = `${link.href}${selectParams}${rangeParams}`;
      link.href = newUrl;
    } else {
      link.href = `${link.href}?SELECT=name&min-value-cost=100&max-value-cost=300000`;
    }
  }
}
