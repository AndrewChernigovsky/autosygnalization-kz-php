<script setup lang="ts">
import Menu from './UI/Menu.vue';
import Header from './components/layout/Header/Header.vue';
import {
  isAuthenticated,
  checkAuthStatus,
  refreshAuthStatus,
} from './router/authGuard';
import MyBtn from './components/UI/MyBtn.vue';
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import profileStore from './stores/profileStore';

const router = useRouter();
const store = profileStore();

onMounted(async () => {
  await checkAuthStatus();
  if (!isAuthenticated.value) {
    router.push('/login');
  } else {
    // Загружаем профиль пользователя если авторизованы
    await store.getProfile();
  }
});
</script>

<template>
  <div v-if="isAuthenticated" class="app-container">
    <Menu />
    <h1 style="text-align: center; margin-bottom: 20px" class="my-title m-0">
      Админ-панель, здравствуйте
      {{ store.profile?.username || 'Администратор' }}!
    </h1>
    <Header />
    <main class="main-content my-page">
      <router-view />
    </main>
  </div>
  <div v-if="!isAuthenticated" class="app-container-error">
    <h1>Вы не авторизованы</h1>
    <MyBtn variant="primary" @click="refreshAuthStatus">
      Перейти на страницу авторизации
    </MyBtn>
  </div>
</template>

<style scoped lang="scss">
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
  padding: 20px;
  overflow-y: auto;
  transition: all 0.3s ease;
}

.app-container-error {
  width: 100%;
  height: 100%;
  flex-direction: column;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
