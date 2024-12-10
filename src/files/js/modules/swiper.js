export function initSwiper() {
  const swiperIntro = document.querySelector('.swiper-intro');
  const titles = document.querySelectorAll('.title');

  if (swiperIntro) {
    const swiper = new Swiper(swiperIntro, {
      loop: true,
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

    window.swiperIntro = swiper;
    swiper.on('slideChange', () => {
      // Сначала скрываем все заголовки
      titles.forEach(title => {
        title.classList.remove('visible');
      });

      // Получаем новый активный слайд и показываем его заголовок
      const slide = swiper.slides[swiper.activeIndex];
      const title = slide.querySelector('.title');

      if (title) {
        title.classList.add('visible'); // Добавляем класс для анимации
      }
    });

    // Инициализация для первого слайда
    const initialSlide = swiper.slides[swiper.activeIndex];
    const initialTitle = initialSlide.querySelector('.title');
    if (initialTitle) {
      initialTitle.classList.add('visible'); // Показываем заголовок первого слайда
    }
  }
}
// videos.forEach(video => {
//   if (!video.classList.contains('.swiper-slide-active')) {
//     console.log(video.querySelector('video').currentTime);
//     video.querySelector('video').currentTime = 0;
//   }
// }
// )