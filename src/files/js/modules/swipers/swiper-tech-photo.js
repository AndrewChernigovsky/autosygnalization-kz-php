import Swiper from 'swiper';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';

export function initSwiperTechPhoto() {
  const swiperTechPhoto = document.querySelector('.swiper-tech-photo');

  let techPhotoSwiper;

  function createTechPhotoSwiper() {
    if (!techPhotoSwiper && swiperTechPhoto) {
      techPhotoSwiper = new Swiper(swiperTechPhoto, {
        loop: true,
        modules: [Autoplay, Pagination, EffectFade],
        effect: 'fade',
        speed: 1000,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        slidesPerView: 1,
        // breakpoints: {
        //   768: {
        //     slidesPerView: 2,
        //   }
        // }
      });
    }
  }

  function destroyTechPhotoSwiper() {
    if (techPhotoSwiper) {
      techPhotoSwiper.destroy(true, true);
      techPhotoSwiper = null;
    }
  }

  createTechPhotoSwiper();

  function checkWindowSize() {
    if (window.innerWidth <= 768) {
      createTechPhotoSwiper();
    } else {
      destroyTechPhotoSwiper();
    }
  }

  checkWindowSize();
  window.addEventListener('resize', checkWindowSize);
}
