// const tabs = document.querySelectorAll('#tarifs-tabs li button');
// const tabsItems = document.querySelectorAll('#tarifs-tabs-items .tarifs__list-item ')


// export function initTarifsTabs() {
//   function clearReset() {
//     Array.from(tabs).forEach((tab, index) => {
//       tab.parentElement.classList.remove('active');
//       tabsItems[index].classList.remove('active');
//     });
//   }

//   if (tabs && tabsItems) {
//     console.log(tabs);
//     console.log(tabsItems);
//     Array.from(tabs).forEach((tab, index) => {
//       tab.addEventListener('click', () => {
//         clearReset()
//         tab.parentElement.classList.toggle('active')
//         tabsItems[index].classList.toggle('active')
//       })
//     });
//   }
//   return;
// }

export function showTabs() {
  document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('.tab__button');
  const tabLists = document.querySelectorAll('.tab__list');

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
console.log(message)
