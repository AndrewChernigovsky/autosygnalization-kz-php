import Swiper from "swiper";
import { Autoplay, Pagination, EffectCards } from "swiper/modules";

export function initSwiperSertificates() {
  const swiperSertificates = document.querySelector('.swiper-sertificates');

  let sertificatesSwiper;

  function createSertificatesSwiper() {
    if (!sertificatesSwiper && swiperSertificates) {
      sertificatesSwiper = new Swiper(swiperSertificates, {
        loop: true,
        modules: [Autoplay, Pagination],
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        spaceBetween: 10,
        slidesPerView: 1.5,
        // centeredSlides: true,
        breakpoints: {
          768: {
            slidesPerView: 2.5,
            spaceBetween: 20,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 30,
          }
        },
      });
    }
  }
  createSertificatesSwiper();
}
