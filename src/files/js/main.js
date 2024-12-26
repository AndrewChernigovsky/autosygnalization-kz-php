const feedbackForm = document.getElementById("feedback-form");
const fancyboxExist = document.querySelectorAll("[data-fancybox");
const searchExist = document.getElementById("search");
const phoneButton = document.querySelector(".phone-button");

async function loadModule() {
  const { toToggleMenu } = await import("./modules/menu-burger.js");
  const { initSwiper } = await import("./modules/swiper.js");
  const { toggleList } = await import("./modules/footer-menu.js");
  const { cartButtonHandler } = await import('./modules/cart-button.js');

  toToggleMenu();
  toggleList();

  setTimeout(initSwiper, 100);

  if (phoneButton != null) {
    const { initPhone } = await import("./modules/phone-button.js");
    initPhone();
  }
  if (searchExist != null) {
    const { initSearch } = await import("./modules/search.js");
    initSearch();
  }
  if (feedbackForm != null) {
    const { validateSectionForm } = await import("./modules/validate-form.js");
    const { initValidate } = await import("./modules/initValidate.js");
    validateSectionForm();
    initValidate();
  }
  if (fancyboxExist.length > 0) {
    const { initFancybox } = await import("./modules/fancybox.js");
    initFancybox();
  }
  cartButtonHandler()
}

document.addEventListener("DOMContentLoaded", loadModule);

function showTabs() {
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

showTabs();

const PRODUCTION = window.location.href.includes('/dist/');
const url = `${PRODUCTION ? '/dist/' : '/'}files/php/api/sessions/session-destroy.php`;
sessionStorage.setItem("is_reloaded", true);

window.addEventListener("unload", function () {
  if (sessionStorage.getItem("is_reloaded") !== 'true') {
    navigator.sendBeacon(url, "");
  }
});
