import Swiper from "swiper";
import { Autoplay, Pagination, EffectFade, Navigation } from "swiper/modules";

export function initSwiperOffers() {
  const swiperOffers = document.querySelector('.swiper-offers');
  function createServiceSwiper() {
    if (swiperOffers) {
      new Swiper(swiperOffers, {
        loop: true,
        modules: [Autoplay, Pagination, EffectFade, Navigation],
        effect: 'fade',
        fadeEffect: {
          crossFade: true,
        },
        speed: 1000,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          prevEl: '.offers__button--prev',
          nextEl: '.offers__button--next',
        },
        slidesPerView: 1,
      });
    }
  }

  createServiceSwiper();
}
console.log(document.querySelector('.swiper-button-prev'));
console.log(document.querySelector('.swiper-button-next'));

