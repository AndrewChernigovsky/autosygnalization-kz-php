export function showTabs() {
  const buttons = document.querySelectorAll('.tab__button');
  const tabLists = document.querySelectorAll('.tab__list');

  buttons.forEach(button => {
    button.addEventListener('click', () => {
      buttons.forEach(btn => btn.classList.remove('tab__button--active'));
      button.classList.add('tab__button--active');
      const tabId = button.getAttribute('data-tab');
      tabLists.forEach(list => list.getAttribute('data-content') === tabId ? list.classList.add('tab__list--show') : list.classList.remove('tab__list--show'));
    });
  });
};