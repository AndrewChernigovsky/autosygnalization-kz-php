import { createRouter, createWebHistory } from 'vue-router';
import { checkAuthGuard } from './authGuard';
import Home from '../views/Home.vue';
import Products from '../views/Products.vue';
import Services from '../views/Services.vue';
import MyContacts from '../views/MyContacts.vue';
import AboutUs from '../views/AboutUs.vue';
import Advantage from '../views/Advantage.vue';
import Sertificates from '../views/Sertificates.vue';
import MyMainNav from '../views/MyMainNav.vue';
import Works from '../views/Works.vue';
import Footer from '../views/Footer.vue';
import Registrations from '../views/Registrations.vue';
import IntroSlide from '../components/IntroSlide/IntroSlide.vue';
import Docs from '../views/Docs.vue';

export const routes = [
  {
    path: '/main-page',
    name: 'Главная',
    component: Home,
    beforeEnter: checkAuthGuard, // Защищаем главную страницу
  },
  {
    path: '/intro-slide-page',
    name: 'Слайдер',
    component: IntroSlide,
    beforeEnter: checkAuthGuard, // Защищаем слайдер
  },
  {
    path: '/navigation-page',
    name: 'Навигация',
    component: MyMainNav,
    beforeEnter: checkAuthGuard, // Защищаем навигацию
  },
  {
    path: '/contacts-page',
    name: 'Контакты',
    component: MyContacts,
    beforeEnter: checkAuthGuard, // Защищаем контакты
  },
  {
    path: '/products-page',
    name: 'Продукты',
    component: Products,
    beforeEnter: checkAuthGuard, // Защищаем продукты
  },
  {
    path: '/services-page',
    name: 'Услуги',
    component: Services,
    beforeEnter: checkAuthGuard, // Защищаем услуги
  },
  {
    path: '/about-us-page',
    name: 'О нас',
    component: AboutUs,
    beforeEnter: checkAuthGuard, // Защищаем о нас
  },
  {
    path: '/advantages-page',
    name: 'Преимущества',
    component: Advantage,
    beforeEnter: checkAuthGuard, // Защищаем преимущества
  },
  {
    path: '/sertificates-page',
    name: 'Сертификаты',
    component: Sertificates,
    beforeEnter: checkAuthGuard, // Защищаем сертификаты
  },
  {
    path: '/works-page',
    name: 'Работы',
    component: Works,
    beforeEnter: checkAuthGuard, // Защищаем работы
  },
  {
    path: '/footer-page',
    name: 'Футер',
    component: Footer,
    beforeEnter: checkAuthGuard, // Защищаем футер
  },
  {
    path: '/registrations-page',
    name: 'Профиль',
    component: Registrations,
    beforeEnter: checkAuthGuard, // Защищаем профиль
  },
  {
    path: '/docs-page',
    name: 'Документы',
    component: Docs,
    beforeEnter: checkAuthGuard, // Защищаем документы
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
