<script setup lang="ts">
import Menu from './UI/Menu.vue';
<<<<<<< HEAD
=======
// import test from './views/test.vue';

const isSidebarOpen = ref(false);
const iframeStore = useIframeStore();
const hasSidebarBeenOpened = ref(false);

const iframeKey = computed(() => iframeStore.refreshKey);

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
  if (isSidebarOpen.value && !hasSidebarBeenOpened.value) {
    hasSidebarBeenOpened.value = true;
  }
}
>>>>>>> 82a4a47595830b048a1fc4b09ce8743992d95a97
</script>

<template>
  <div class="app-container">
    <Menu />
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

.sidebar-content {
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.main-content {
  flex-grow: 1;
  padding: 20px;
  overflow-y: auto;
  transition: all 0.3s ease;
}
</style>
