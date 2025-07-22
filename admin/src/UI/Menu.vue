<template>
  <div>
    <button @click="toggleMenu" class="burger-menu">
      <span class="burger-menu__line"></span>
      <span class="burger-menu__line"></span>
      <span class="burger-menu__line"></span>
    </button>
    <div class="menu" :class="{ 'is-open': isOpen }">
      <ul class="menu__list">
        <li class="menu__item">
          <router-link to="/" class="menu__link" @click="closeMenu"
            >Главная</router-link
          >
        </li>
        <li class="menu__item">
          <router-link to="/slider-intro" class="menu__link" @click="closeMenu"
            >Слайдер</router-link
          >
        </li>
        <li class="menu__item">
          <router-link to="/navigation" class="menu__link" @click="closeMenu"
            >Навигация</router-link
          >
        </li>
        <li class="menu__item">
          <router-link to="/contacts" class="menu__link" @click="closeMenu"
            >Контакты</router-link
          >
        </li>
        <li class="menu__item">
          <router-link to="/ui" class="menu__link" @click="closeMenu"
            >UI</router-link
          >
        </li>
        <li class="menu__item">
          <router-link to="/about-us" class="menu__link" @click="closeMenu"
            >О нас</router-link
          >
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const isOpen = ref(false);

function toggleMenu() {
  isOpen.value = !isOpen.value;
}

function closeMenu() {
  isOpen.value = false;
}
</script>

<style scoped lang="scss">
.burger-menu {
  position: fixed;
  top: 30px;
  right: 30px;
  z-index: 1200;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  width: 30px;
  height: 24px;

  &__line {
    width: 100%;
    height: 3px;
    background-color: #ffffff;
    border-radius: 2px;
  }
}

.menu {
  position: fixed;
  top: 0px;
  right: 0px;
  width: 250px;
  height: 100vh;
  background-color: #1a1a1a;
  transform: translateX(100%);
  transition: transform 0.3s ease-in-out;
  z-index: 10;
  padding-top: 60px;

  &.is-open {
    transform: translateX(0%);
  }

  &__list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  &__item {
    padding: 0;
  }

  &__link {
    display: block;
    padding: 15px 20px;
    color: #fff;
    text-decoration: none;
    font-size: 18px;
    transition: background-color 0.2s;

    &:hover {
      background-color: #333;
    }

    &.router-link-active {
      color: orange;
      background-color: #2a2a2a;
    }
  }
}
</style>
