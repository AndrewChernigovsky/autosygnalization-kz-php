import Swiper from 'swiper';
import { Thumbs } from 'swiper/modules';

export function initSwiperArticle() {
  alert(1);
  const thumbsSlider = new Swiper('.thumbs', {
    spaceBetween: 10,
    slidesPerView: 3,
    loop: true,
  }); 

  const bigSlider = new Swiper('.big-slider', {
    loop: true,
    modules: [Thumbs],
    thumbs: {
      swiper: thumbsSlider,
    },
  });  
}
