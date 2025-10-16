import Swiper from "swiper";
import { Autoplay, Pagination, EffectFade } from "swiper/modules";

export function initSwiperService() {
  const swiperService = document.querySelector('.swiper-service');

  let serviceSwiper;

  function createServiceSwiper() {
    if (!serviceSwiper && swiperService) {
      serviceSwiper = new Swiper(swiperService, {
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

  function destroyServiceSwiper() {
    if (serviceSwiper) {
      serviceSwiper.destroy(true, true);
      serviceSwiper = null;
    }
  }

  createServiceSwiper();

  function checkWindowSize() {
    if (window.innerWidth < 768) {
      createServiceSwiper();
    } else {
      destroyServiceSwiper()
    }
  }

  checkWindowSize();
  window.addEventListener('resize', checkWindowSize);
}
