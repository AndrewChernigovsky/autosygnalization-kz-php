import Swiper from "swiper";
import { Autoplay, Pagination, EffectFade, Navigation } from "swiper/modules";

export function initSwiperOffers() {
  const swiperOffers = document.querySelectorAll('.swiper-offers');

  function createServiceSwiper() {
    if (swiperOffers.length > 0) {
      swiperOffers.forEach((swiperElement) => {
        // Найдите пагинацию и навигацию внутри текущего слайдера
        const pagination = swiperElement.querySelector('.swiper-pagination');
        const prevButton = swiperElement.querySelector('.swiper-button-prev-offers');
        const nextButton = swiperElement.querySelector('.swiper-button-next-offers');

        new Swiper(swiperElement, {
          loop: true,
          modules: [Autoplay, Pagination, EffectFade, Navigation],
          effect: 'fade',
          fadeEffect: {
            crossFade: true,
          },
          speed: 1000,
          pagination: {
            el: pagination, // Уникальная пагинация для каждого слайдера
            clickable: true,
          },
          navigation: {
            prevEl: prevButton, // Уникальная кнопка "назад" для каждого слайдера
            nextEl: nextButton, // Уникальная кнопка "вперёд" для каждого слайдера
          },
          slidesPerView: 1,
          grabCursor: true,

          on: {
            slideChange: function () {
              console.log('Слайд изменился:', this.activeIndex);
            }
          }
        });
      });
    }
  }
  createServiceSwiper();
}
