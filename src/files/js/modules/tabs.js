export function showTabs() {
  document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.tab__button');
    const tabLists = document.querySelectorAll('.tab__list');

    if (buttons.length > 0) {
      buttons[0].classList.add('tab__button--active');
      const firstTabId = buttons[0].getAttribute('data-tab');

      tabLists.forEach(list => {
        if (list.getAttribute('data-content') === firstTabId) {
          list.classList.add('tab__list--show');
        } else {
          list.classList.remove('tab__list--show');
        }
      });
    }

    buttons.forEach(button => {
      button.addEventListener('click', () => {
        buttons.forEach(btn => btn.classList.remove('tab__button--active'));
      
        button.classList.add('tab__button--active');

        const tabId = button.getAttribute('data-tab');

        tabLists.forEach(list => {
          if (list.getAttribute('data-content') === tabId) {
            list.classList.add('tab__list--show');
          } else {
            list.classList.remove('tab__list--show');
          }
        });
      });
    });
  });
}
