async function loadModule() {
  const { toToggleMenu } = await import("./modules/menu-burger.js");
  const { initSwiper } = await import("./modules/swiper.js");
  const { initFormValidation } = await import("./modules/validation.js");
  const { initSearch } = await import("./modules/search.js");
  const { toggleList } = await import("./modules/footer-menu.js");

  toToggleMenu()
  initSwiper();
  initFormValidation();
  initSearch();
  toggleList();
}

document.addEventListener('DOMContentLoaded', loadModule)
