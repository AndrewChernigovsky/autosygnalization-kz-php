<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();

const navItems = router.getRoutes();

const isActive = (path: string) => {
  return route.path === path;
};
</script>

<template>
  <header class="my-header">
    <div class="my-header-logo">
      <img
        src="../../../assets/logo.png"
        alt="logo"
        class="my-logo"
        width="250"
        height="75"
      />
    </div>
    <nav class="my-nav">
      <ul class="list-style-none p-0 m-0">
        <li v-for="item in navItems" :key="item.path">
          <router-link
            :to="item.path"
            :class="['nav-link', 'my-link', { active: isActive(item.path) }]"
          >
            {{ item.name }}
          </router-link>
        </li>
      </ul>
    </nav>
  </header>
</template>

<style scoped>
.my-header {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 20px;
}

.my-nav ul {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
  justify-content: center;
}

.nav-link {
  font-size: 32px;
  line-height: 42px;
  color: #fff;
  padding: 8px 16px;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.nav-link:hover {
  opacity: 0.7;
}

.nav-link.active {
  position: relative;
}

.nav-link.active::after {
  content: '';
  display: block;
  width: calc(100% - 16px * 2);
  height: 2px;
  background-color: #fff;
  position: absolute;
  bottom: 8px;
  left: 16px;
  border-radius: 4px;
}
</style>
