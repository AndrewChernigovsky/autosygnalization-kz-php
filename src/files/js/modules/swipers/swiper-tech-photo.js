import Swiper from 'swiper';
import { Autoplay, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';

export function initSwiperTechPhoto() {
  const swiperTechPhoto = new Swiper('.swiper-tech-photo', {
    modules: [Autoplay, Pagination],
    loop: true,
    slidesPerView: 1,
    spaceBetween: 20,
    speed: 800,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.tech-photo__swiper-pagination',
      clickable: true,
    },
    wrapperClass: 'tech-photo__swiper-wrapper',
    slideClass: 'tech-photo__swiper-slide',
    watchOverflow: true,
    roundLengths: true,
    breakpoints: {
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      }
    }
  });

  return swiperTechPhoto;
}
