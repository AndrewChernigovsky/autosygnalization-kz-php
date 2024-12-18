export function initSwiperIntro() {
  const swiperIntro = document.querySelector('.swiper-intro');
  let introSwiper;

  function createIntroSwiper() {
    if (!introSwiper && swiperIntro) {
      introSwiper = new Swiper(swiperIntro, {
        loop: true,
        allowTouchMove: false,
        modules: [Autoplay, Pagination, EffectFade],
        effect: 'fade',
        speed: 1000,
        autoplay: {
          delay: 6000,
          disableOnInteraction: false,
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
  createIntroSwiper();
}
