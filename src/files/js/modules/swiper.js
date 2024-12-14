export function initSwiper() {
  const swiperIntro = document.querySelector('.swiper-intro');
  const swiperService = document.querySelector('.swiper-service');
  const titles = document.querySelectorAll('.title');

  if (swiperIntro) {
    const introSwiper = new Swiper(swiperIntro, {
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

    window.swiperIntro = introSwiper;
    introSwiper.on('slideChange', () => {
      titles.forEach(title => {
        title.classList.remove('visible');
      });

      const slide = introSwiper.slides[introSwiper.activeIndex];
      const title = slide.querySelector('.title');

      if (title) {
        title.classList.add('visible');
      }
    });

    const initialSlide = introSwiper.slides[introSwiper.activeIndex];
    const initialTitle = initialSlide.querySelector('.title');
    if (initialTitle) {
      initialTitle.classList.add('visible');
    }
  }
  if (swiperService) {
    const serviceSwiper = new Swiper(swiperService, {
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

    window.swiperService = serviceSwiper;
  }
}
