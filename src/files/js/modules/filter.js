export function filterToggleMenu() {
  const filterBtn = document.getElementById('filter-btn');
  const filterCatalog = document.getElementById('filter-catalog');
  const filterBtnClose = document.getElementById('filter-btn-close');
  let flag = false;

  filterBtn.addEventListener('click', () => {
    filterCatalog.classList.toggle('open');
    filterBtnClose.style.display = 'flex';
    flag = true;
  });

  filterBtnClose.addEventListener('click', () => {
    filterCatalog.classList.toggle('open');
    filterBtnClose.style.display = 'none';
  });
}
