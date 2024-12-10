async function loadModule() {
  const { toToggleMenu } = await import("./modules/menu-burger.js");
  const { initSwiper } = await import("./modules/swiper.js");
  const { initFormValidation } = await import("./modules/validation.js");
  const { initSearch } = await import("./modules/search.js");

  toToggleMenu()
  initSwiper();
  initFormValidation();
  initSearch();

}

document.addEventListener('DOMContentLoaded', loadModule)
