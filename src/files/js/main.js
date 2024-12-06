async function loadModule() {
  const { toToggleMenu } = await import("./modules/menu-burger.js");
  const { initTarifsTabs } = await import("./modules/tabs.js");
  const { initSwiper } = await import("./modules/swiper.js");
  const { initFormValidation } = await import("./modules/validation.js");

  toToggleMenu()
  initTarifsTabs()
  initSwiper();
  initFormValidation();
}

document.addEventListener('DOMContentLoaded', loadModule)
