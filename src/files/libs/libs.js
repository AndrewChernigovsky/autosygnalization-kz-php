import Swiper from 'swiper';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import Inputmask from 'inputmask';

// Инициализация библиотек
const libraries = {
  Swiper: Swiper,
  Autoplay: Autoplay,
  Pagination: Pagination,
  EffectFade: EffectFade,
  Inputmask: Inputmask
};

// Экспорт библиотек в глобальную область
for (const [key, value] of Object.entries(libraries)) {
  window[key] = value;
}

async function loadLibs() {
  await import('swiper');
  await import('inputmask');
}

loadLibs();