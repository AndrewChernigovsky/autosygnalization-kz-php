export function filterToggleMenu() {
  const filterBtn = document.getElementById('filter-btn');
  const filterCatalog = document.getElementById('filter-catalog');
  const filterBtnClose = document.getElementById('filter-btn-close');
  let flag = false;

  function checkWindowSize() {
    const isMobile = window.innerWidth <= 1024;
    if (isMobile) {
      filterCatalog.classList.remove('open');
      filterBtnClose.style.display = 'none';
    }
  }

  function toggleFiltersMenu() {
    if (flag) {
      filterCatalog.classList.remove('open');
      filterBtnClose.style.display = 'none';
      flag = false;
    } else {
      filterCatalog.classList.add('open');
      filterBtnClose.style.display = 'flex';
      flag = true;
    }
  }

  filterBtn.addEventListener('click', () => {
    toggleFiltersMenu()
  });

  filterBtnClose.addEventListener('click', () => {
    toggleFiltersMenu()
  });
  checkWindowSize();
  window.addEventListener('resize', checkWindowSize);
}
