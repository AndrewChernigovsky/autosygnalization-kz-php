export function initSelect() {
  const selected = document.querySelector('.select-selected');
  const item = document.querySelector('.select-items');
  const container = document.querySelector('.custom-select');
  const options = item.querySelectorAll('div');
  const rect = container.getBoundingClientRect();
  const width = rect.width;

  options.forEach((option) => (option.style.width = `${width}px`));
  selected.addEventListener('click', function () {
    item.classList.toggle('select-hide');
    selected.classList.toggle('open');

    const handleClickOutside = function (event) {
      if (!selected.contains(event.target) && !item.contains(event.target)) {
        item.classList.add('select-hide');
        selected.classList.remove('open');
        document.removeEventListener('click', handleClickOutside);
      }
    };

    document.addEventListener('click', handleClickOutside);
  });

  item.addEventListener('click', function (event) {
    if (event.target.matches('div')) {
      selected.innerHTML = event.target.innerHTML;
      item.classList.add('select-hide');
      selected.classList.remove('open');
    }
  });
}
