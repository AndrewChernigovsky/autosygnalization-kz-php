import Swiper from 'swiper';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';

export function initSwiperReviews() {
  const swiperReviews = document.querySelector('.swiper-reviews');

  function createReviewsSwiper() {
    if (swiperReviews) {
      console.log(swiperReviews);
      reviewsSwiper = new Swiper(swiperReviews, {
        loop: true,
        modules: [Autoplay, Pagination, EffectFade],
        speed: 1000,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        spaceBetween: 10,
        slidesPerView: 1,
        breakpoints: {
          768: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3
          }
        }
      });
    }
  }

  createReviewsSwiper();
}
