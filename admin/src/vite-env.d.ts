/// <reference types="vite/client" />

// Декларация типов для Vue компонентов, чтобы устранить ошибки линтера
declare module '*.vue' {
  import { DefineComponent } from 'vue';
  const component: DefineComponent<{}, {}, any>;
  export default component;
}
