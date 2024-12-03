const tabs = document.querySelectorAll('#tarifs-tabs li button');
const tabsItems = document.querySelectorAll('#tarifs-tabs-items .tarifs__list-item ')


export function initTarifsTabs() {
  function clearReset() {
    Array.from(tabs).forEach((tab, index) => {
      tab.parentElement.classList.remove('active');
      tabsItems[index].classList.remove('active');
    });
  }

  if (tabs && tabsItems) {
    console.log(tabs);
    console.log(tabsItems);
    Array.from(tabs).forEach((tab, index) => {
      tab.addEventListener('click', () => {
        clearReset()
        tab.parentElement.classList.toggle('active')
        tabsItems[index].classList.toggle('active')
      })
    });
  }
  return;
}