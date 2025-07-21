<script setup lang="ts">
import Header from '../components/layout/Header/Header.vue';
import MyInput from '../components/UI/MyInput.vue';
</script>

<template>
  <div class="my-page p-20">
    <Header />
    <div class="row">
      <div class="col-12">
        <h1>UI</h1>
        <MyInput type="file" />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
