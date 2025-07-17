<script setup lang="ts">
import { ref, computed } from 'vue';
import { useIframeStore } from './stores/iframeStore';
import Menu from './UI/Menu.vue';

const isSidebarOpen = ref(false);
const iframeStore = useIframeStore();

const iframeKey = computed(() => iframeStore.refreshKey);

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
}
</script>

<template>
  <div class="app-container">
    <Menu />
    <aside :class="{ 'sidebar-open': isSidebarOpen }">
      <button @click="toggleSidebar" class="toggle-btn">
        {{ isSidebarOpen ? '◀' : '▶' }}
      </button>
      <div class="sidebar-content">
        <iframe
          :key="iframeKey"
          src="http://localhost:3000/"
          width="100%"
          height="100%"
          frameborder="0"
        ></iframe>
      </div>
    </aside>
    <main class="main-content">
      <router-view />
    </main>
  </div>
</template>

<style scoped lang="scss">
.app-container {
  display: flex;
  height: 100vh;
}

aside {
  width: 50px;
  height: 100%;
  position: relative;
  transition: width 0.3s ease;
  overflow: hidden;
  border-right: 1px solid #ccc;
}

aside.sidebar-open {
  width: 100vw;
}

.toggle-btn {
  position: absolute;
  top: 50%;
  right: 0px;
  transform: translateY(-50%);
  z-index: 1000;
  background: #007bff;
  color: white;
  border: none;
  width: 25px;
  height: 50px;
  cursor: pointer;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 5px 0 0 5px;
}

aside.sidebar-open .toggle-btn {
  right: 0;
}

.sidebar-content {
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: opacity 0.3s ease;
}

aside.sidebar-open .sidebar-content {
  opacity: 1;
}

iframe {
  display: block;
}

.main-content {
  flex-grow: 1;
  padding: 20px;
  overflow-y: auto;
  transition: all 0.3s ease;
}

aside.sidebar-open + .main-content {
  display: none;
}
</style>
