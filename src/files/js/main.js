const feedbackForm = document.getElementById("feedback-form");
const fancyboxExist = document.querySelectorAll("[data-fancybox");
const searchExist = document.getElementById("search");
const phoneButton = document.querySelector(".phone-button");
const moduleCache = {};

async function loadModule(moduleName) {
  if (moduleCache[moduleName]) {
    return moduleCache[moduleName];
  }

  const module = await import(`./modules/${moduleName}.js`);

  moduleCache[moduleName] = module;

  return module;
}


async function loadModules() {
  const { toToggleMenu } = await import("./modules/menu-burger.js");
  const { initSwiper } = await import("./modules/swiper.js");
  const { toggleList } = await import("./modules/footer-menu.js");
  const { cartButtonHandler } = await import('./modules/cart-button.js');

  toToggleMenu();
  toggleList();
  setTimeout(initSwiper, 100);

  if (phoneButton != null) {
    if (moduleCache[moduleName]) {
      return moduleCache[moduleName];
    }
    const { initPhone } = await loadModule("./modules/phone-button");
    initPhone();
  }
  if (searchExist != null) {
    const { initSearch } = await loadModule("./modules/search");
    initSearch();
  }
  if (feedbackForm != null) {
    const { validateSectionForm } = await loadModule("./modules/validate-form");
    const { initValidate } = await loadModule("./modules/initValidate");
    validateSectionForm();
    initValidate();
  }
  if (fancyboxExist.length > 0) {
    const { initFancybox } = await loadModule("./modules/fancybox");
    initFancybox();
  }
  cartButtonHandler()
}

document.addEventListener("DOMContentLoaded", loadModules);

const PRODUCTION = window.location.href.includes('/dist/');
const url = `${PRODUCTION ? '/dist/' : '/'}files/php/api/sessions/session-destroy.php`;
sessionStorage.setItem("is_reloaded", true);

window.addEventListener("unload", function () {
  if (sessionStorage.getItem("is_reloaded") !== 'true') {
    navigator.sendBeacon(url, "");
  }
});
