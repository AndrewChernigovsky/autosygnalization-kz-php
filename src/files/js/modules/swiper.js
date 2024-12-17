import { EffectCards, Navigation } from "swiper/modules";

export function initSwiper() {
  const swiperIntro = document.querySelector('.swiper-intro');
  const swiperService = document.querySelector('.swiper-service');
  const swiperSertificates = document.querySelector('.swiper-sertificates');
  const swiperAutosygnals = document.querySelector('.swiper-autosygnals');
  const swiperPopular = document.querySelector('.swiper-popular');
  const swiperPopularGallery = document.querySelectorAll('.popular__item .swiper-popular-gallery');

  let introSwiper;
  let serviceSwiper;
  let sertificatesSwiper;
  let autosygnalsSwiper;
  let popularSwiper;
  let popularGallerySwiper;

  function createIntroSwiper() {
    if (!introSwiper && swiperIntro) {
      introSwiper = new Swiper(swiperIntro, {
        loop: true,
        modules: [Autoplay, Pagination, EffectFade],
        effect: 'fade',
        speed: 1000,
        autoplay: {
          delay: 6000,
          disableOnInteraction: true,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        slidesPerView: 1,
      });

      introSwiper.on('slideChange', () => {
        const titles = document.querySelectorAll('.intro__title');
        titles.forEach(title => {
          title.classList.remove('visible');
        });

        const slide = introSwiper.slides[introSwiper.activeIndex];
        const title = slide.querySelector('.intro__title');

        if (title) {
          title.classList.add('visible');
        }
      });

      const initialSlide = introSwiper.slides[introSwiper.activeIndex];
      const initialTitle = initialSlide.querySelector('.intro__title');
      if (initialTitle) {
        initialTitle.classList.add('visible');
      }
    }
  }

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

  function createServiceSwiper() {
    if (!serviceSwiper && swiperService) {
      serviceSwiper = new Swiper(swiperService, {
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

  function createSertificatesSwiper() {
    if (!sertificatesSwiper && swiperSertificates) {
      sertificatesSwiper = new Swiper(swiperSertificates, {
        loop: true,
        modules: [Autoplay, Pagination, EffectCards],
        // effect: 'cards',
        // grabCursor: true,
        // cardsEffect: {
        //   slideShadows: true,
        //   perSlideOffset: 50,
        // },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        spaceBetween: 10,
        slidesPerView: 1.5,
        centeredSlides: true,
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

  function createAutosygnalsSwiper() {
    if (!autosygnalsSwiper && swiperAutosygnals) {
      console.log('Функция');
      autosygnalsSwiper = new Swiper(swiperAutosygnals, {
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

  function destroyServiceSwiper() {
    if (serviceSwiper) {
      serviceSwiper.destroy(true, true);
      serviceSwiper = null;
    }
  }

  function destroyAutosygnalsSwiper() {
    if (autosygnalsSwiper) {
      autosygnalsSwiper.destroy(true, true);
      autosygnalsSwiper = null;
    }
  }

  function checkWindowSize() {
    if (window.innerWidth <= 768) {
      createServiceSwiper();
      createAutosygnalsSwiper()
    } else {
      destroyServiceSwiper()
      destroyAutosygnalsSwiper();
    }
  }

  createIntroSwiper();
  createSertificatesSwiper();
  createAutosygnalsSwiper();
  createPopularGallerySwiper();
  createPopularSwiper();
  checkWindowSize();
  window.addEventListener('resize', checkWindowSize);
}
