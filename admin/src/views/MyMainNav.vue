<script setup lang="ts">
import { onMounted } from 'vue';
import LoadingModal from '../components/UI/LoadingModal.vue';
import mainNavStore from '../stores/mainNavStore';
import AddNewNav from '../components/MainNav/AddNewNav.vue';
import MainNavList from '../components/MainNav/MainNavList.vue';

const store = mainNavStore();

onMounted(() => {
  store.getNavItems();
  store.getAvailablePages();
});
</script>

<template>
  <section class="navigation">
    <h2 class="navigation-title my-title m-0">Навигация</h2>
    <LoadingModal
      v-if="store.isLoading || store.error"
      :isLoading="store.isLoading"
      :error="store.error"
      :retry="store.getNavItems"
    />
    <div class="navigation-wrapper" v-else>
      <AddNewNav />
      <MainNavList />
    </div>
  </section>
</template>

<style scoped>
.navigation {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.navigation-wrapper {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
</style>
