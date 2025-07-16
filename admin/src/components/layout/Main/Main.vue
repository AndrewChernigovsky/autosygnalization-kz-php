<script setup lang="ts">
import Navigation from '../../Navigation/Navigation.vue';
import Contact from '../../Contact/Contact.vue';
</script>

<template>
  <div class="main">
    <Navigation />
    <Contact />
  </div>
</template>

<style scoped>
.main {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
}
</style>
