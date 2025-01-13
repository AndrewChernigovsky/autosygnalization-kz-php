export function initSelect() {
  const selected = document.querySelector('.select-selected');
  const item = document.querySelector('.select-items');
  const options = item.querySelectorAll('.select-item');
  let value;

  function setDefaultSelect(object, container) {
    if (object !== null && object !== undefined) {
      for (let element of object) {
        if (element.classList.contains('default')) {
          container.innerHTML = element.innerHTML;
          container.dataset.value = element.dataset.value;
        }
      }
      return true;
    } else {
      return false;
    }
  }

  setDefaultSelect(options, selected);

  selected.addEventListener('click', function () {
    item.classList.toggle('select-hide');
    selected.classList.toggle('open');
    const currentRect = selected.getBoundingClientRect();
    const currentWidth = currentRect.width;
    options.forEach((option) => (option.style.width = `${currentWidth}px`));

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
      value = event.target.dataset.value;
      console.log(value);
    }
  });
}
