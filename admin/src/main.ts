import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import Home from './views/Home.vue';
import SliderIntro from './views/SliderIntro.vue';
import Navigation from './views/Navigation.vue';
import Contacts from './views/Contacts.vue';
import AboutUs from './views/AboutUs.vue';
import UI from './views/UI.vue';
import './style.css';

const app = createApp(App);
const pinia = createPinia();

// Создаем маршруты
const routes = [
  { path: '/', component: Home, meta: { title: 'Главная' } },
  { path: '/slider-intro', component: SliderIntro, meta: { title: 'Слайдер' } },
  { path: '/navigation', component: Navigation, meta: { title: 'Навигация' } },
  { path: '/contacts', component: Contacts, meta: { title: 'Контакты' } },
  { path: '/about-us', component: AboutUs, meta: { title: 'О нас' } },
  { path: '/ui', component: UI, meta: { title: 'UI элементы' } },
];

// Создаем роутер
const router = createRouter({
  history: createWebHistory(),
  routes,
});

app.use(router);
app.use(pinia);
app.mount('#app');
