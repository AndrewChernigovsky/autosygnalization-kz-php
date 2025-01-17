import Swiper from "swiper";
import { Autoplay, Pagination, EffectFade } from "swiper/modules";

export function initSwiperOffers() {
  const swiperOffers = document.querySelector('.swiper-offers');
  function createServiceSwiper() {
    if (swiperOffers) {
      new Swiper(swiperOffers, {
        loop: true,
        modules: [Autoplay, Pagination, EffectFade],
        effect: 'fade',
        speed: 1000,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          prev: '.swiper-button-prev',
          next: '.swiper-button-next'
        },
        slidesPerView: 1,
      });
    }
  }

  createServiceSwiper();
}
