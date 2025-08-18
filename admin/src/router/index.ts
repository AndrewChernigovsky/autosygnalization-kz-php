import { createRouter, createWebHistory } from 'vue-router';
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

export const routes = [
  {
    path: '/main-page',
    name: 'Главная',
    component: Home,
  },
  {
    path: '/intro-slide-page',
    name: 'Слайдер',
    component: IntroSlide,
  },
  {
    path: '/navigation-page',
    name: 'Навигация',
    component: MyMainNav,
  },
  {
    path: '/contacts-page',
    name: 'Контакты',
    component: MyContacts,
  },
  {
    path: '/products-page',
    name: 'Продукты',
    component: Products,
  },
  {
    path: '/services-page',
    name: 'Услуги',
    component: Services,
  },
  {
    path: '/about-us-page',
    name: 'О нас',
    component: AboutUs,
  },
  {
    path: '/advantages-page',
    name: 'Преимущества',
    component: Advantage,
  },
  {
    path: '/sertificates-page',
    name: 'Сертификаты',
    component: Sertificates,
  },
  {
    path: '/works-page',
    name: 'Работы',
    component: Works,
  },
  {
    path: '/footer-page',
    name: 'Футер',
    component: Footer,
  },
  {
    path: '/registrations-page',
    name: 'Профиль',
    component: Registrations,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
