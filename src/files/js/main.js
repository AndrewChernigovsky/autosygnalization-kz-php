async function loadModule() {
  const { toToggleMenu } = await import("./modules/menu-burger.js");
  const { initSwiper } = await import("./modules/swiper.js");
  const { initFormValidation } = await import("./modules/validation.js");
  const { initSearch } = await import("./modules/search.js");
  const { toggleList } = await import("./modules/footer-menu.js");
  const { validateSectionForm } = await import("./modules/validate-form.js");
  const { initFancybox } = await import("./modules/fancybox.js");
  const { initPhone } = await import("./modules/phone-button.js");

  toToggleMenu()
  initSwiper();
  initFormValidation();
  initSearch();
  toggleList();
  validateSectionForm();
  initPhone();
  initFancybox();
}

document.addEventListener('DOMContentLoaded', loadModule)
