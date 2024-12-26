import Swiper from "swiper";
import { Autoplay, Pagination, EffectFade } from "swiper/modules";

export function initSwiperAutosygnal() {
  const swiperAutosygnals = document.querySelector('.swiper-autosygnals');
  let autosygnalsSwiper;

  function createAutosygnalsSwiper() {
    if (!autosygnalsSwiper && swiperAutosygnals) {
      console.log('Функция');
      autosygnalsSwiper = new Swiper(swiperAutosygnals, {
        loop: true,
        modules: [Autoplay, Pagination, EffectFade],
        effect: 'fade',
        speed: 1000,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        slidesPerView: 1,
      });
    }
  }

  function destroyAutosygnalsSwiper() {
    if (autosygnalsSwiper) {
      autosygnalsSwiper.destroy(true, true);
      autosygnalsSwiper = null;
    }
  }

  function checkWindowSize() {
    if (window.innerWidth <= 768) {
      createAutosygnalsSwiper()
    } else {
      destroyAutosygnalsSwiper();
    }
  }

  createAutosygnalsSwiper();

  checkWindowSize();
  window.addEventListener('resize', checkWindowSize);
}
