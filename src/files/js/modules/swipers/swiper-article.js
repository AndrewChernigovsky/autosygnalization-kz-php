import Swiper from 'swiper';
import { Thumbs } from 'swiper/modules';

export function initSwiperArticle() {
  const thumbsSlider = new Swiper('.thumbs', {
    spaceBetween: 10,
    slidesPerView: 4,
    loop: true,
    touchReleaseOnEdges: true,
    breakpoints: {
      1024: {
        direction: 'vertical',
      }
    }
  }); 

  const bigSlider = new Swiper('.big-slider', {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 10,
    modules: [Thumbs],
    thumbs: {
      swiper: thumbsSlider,
    },
  });  
}
