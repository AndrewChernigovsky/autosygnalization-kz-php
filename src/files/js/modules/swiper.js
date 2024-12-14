export function initSwiper() {
  const swiperIntro = document.querySelector('.swiper-intro');
  const swiperService = document.querySelector('.swiper-service');

  let introSwiper;
  let serviceSwiper;

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

  function destroyIntroSwiper() {
    if (introSwiper) {
      introSwiper.destroy(true, true);
      introSwiper = null;
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
  checkWindowSize();

  window.addEventListener('resize', checkWindowSize);
}
