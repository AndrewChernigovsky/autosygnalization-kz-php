import Swiper from 'swiper';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';

export function initSwiperReviews() {
  const swiperReviews = document.querySelector('.swiper-reviews');

  let reviewsSwiper;

  function createReviewsSwiper() {
    if (!reviewsSwiper && swiperReviews) {
      reviewsSwiper = new Swiper(swiperReviews, {
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

  function destroyReviewsSwiper() {
    if (reviewsSwiper) {
      reviewsSwiper.destroy(true, true);
      reviewsSwiper = null;
    }
  }

  createReviewsSwiper();

  function checkWindowSize() {
    if (window.innerWidth <= 768) {
      createReviewsSwiper();
    } else {
      destroyReviewsSwiper();
    }
  }

  checkWindowSize();
  window.addEventListener('resize', checkWindowSize);
}
