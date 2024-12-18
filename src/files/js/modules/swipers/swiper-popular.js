export function initSwiperPopular() {
  const swiperPopular = document.querySelector('.swiper-popular');
  const swiperPopularGallery = document.querySelectorAll('.popular__item .swiper-popular-gallery');

  let popularSwiper;
  let popularGallerySwiper;

  function createPopularSwiper() {
    if (!popularSwiper && swiperPopular) {
      popularSwiper = new Swiper(swiperPopular, {
        modules: [Autoplay, Pagination],
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        slidesPerView: 1,
        spaceBetween: 10,
        breakpoints: {
          615: {
            slidesPerView: 2,
            spaceBetween: 10,
          },
          768: {
            slidesPerView: 3,
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

  function createPopularGallerySwiper() {
    if (!popularGallerySwiper && swiperPopularGallery) {
      swiperPopularGallery.forEach(element => {
        popularGallerySwiper = new Swiper(element, {
          loop: false,
          modules: [Autoplay, Pagination, Navigation],
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
          slidesPerView: 1,
          spaceBetween: 0,
        });
      });
    }
  }

  createPopularGallerySwiper();
  createPopularSwiper();
}
