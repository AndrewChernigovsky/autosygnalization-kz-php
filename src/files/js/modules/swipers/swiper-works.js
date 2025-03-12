import Swiper from 'swiper';
import { Pagination, Autoplay} from 'swiper/modules';

export function initSwiperWorks() {
  const workSwiper = new Swiper('.swiper-works', {
    modules: [Pagination, Autoplay],
    spaceBetween: 10,
    slidesPerView: 1.5,
    centeredSlides: true,
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.works__swiper-pagination',
      clickable: true,
    },
    breakpoints: {
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 30,
      }
    },
  });

  document.querySelector('.works__swiper').addEventListener('click', function (e) {
    const img = e.target.closest('.works__slide-image img');
    if (img) {
      e.preventDefault();
      const modal = document.createElement('div');
      modal.className = 'modal-image';

      const modalImg = document.createElement('img');
      modalImg.src = img.src;

      modal.appendChild(modalImg);
      document.body.appendChild(modal);

      modal.addEventListener('click', function () {
        this.remove();
      });
    }
  })

  return workSwiper;
};
