<script setup lang="ts">
</script>

<template>
  <main class="my-main">
    <router-view />
  </main>
</template>

<style scoped>
.my-main {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  width: 100%;
}
</style>
