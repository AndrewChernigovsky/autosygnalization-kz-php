import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import SliderIntro from '../views/SliderIntro.vue';
import Products from '../views/Products.vue';
import Services from '../views/Services.vue';
import MyContacts from '../views/MyContacts.vue';
import AboutUs from '../views/AboutUs.vue';
import Advantage from '../views/Advantage.vue';
import Sertificates from '../views/Sertificates.vue';
import MyMainNav from '../views/MyMainNav.vue';
import Works from '../views/Works.vue';
import Footer from '../views/Footer.vue';
import NewFooter from '../views/NewFooter.vue';

export const routes = [
  {
    path: '/',
    name: 'Главная',
    component: Home,
  },
  {
    path: '/slider-intro',
    name: 'Слайдер',
    component: SliderIntro,
  },
  {
    path: '/navigation',
    name: 'Навигация',
    component: MyMainNav,
  },
  {
    path: '/contacts',
    name: 'Контакты',
    component: MyContacts,
  },
  {
    path: '/products',
    name: 'Продукты',
    component: Products,
  },
  {
    path: '/services',
    name: 'Услуги',
    component: Services,
  },
  {
    path: '/about-us',
    name: 'О нас',
    component: AboutUs,
  },
  {
    path: '/advantage',
    name: 'Преимущества',
    component: Advantage,
  },
  {
    path: '/sertificates',
    name: 'Сертификаты',
    component: Sertificates,
  },
  {
    path: '/works',
    name: 'Работы',
    component: Works,
  },
  {
    path: '/footer',
    name: 'Футер',
    component: Footer,
  },
  {
    path: '/new-footer',
    name: 'Новый футер',
    component: NewFooter,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
