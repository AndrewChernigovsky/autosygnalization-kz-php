import { EffectCards } from "swiper/modules";

export function initSwiper() {
  const swiperIntro = document.querySelector('.swiper-intro');
  const swiperService = document.querySelector('.swiper-service');
  const swiperSertificates = document.querySelector('.swiper-sertificates');

  let introSwiper;
  let serviceSwiper;
  let sertificatesSwiper;

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

  function destroyServiceSwiper() {
    if (serviceSwiper) {
      serviceSwiper.destroy(true, true);
      serviceSwiper = null;
    }
  }

  function checkWindowSize() {
    if (window.innerWidth <= 768) {
      createServiceSwiper();
    } else {
      destroyServiceSwiper();
    }
  }

  createIntroSwiper();
  createSertificatesSwiper();
  checkWindowSize();

  window.addEventListener('resize', checkWindowSize);
}
