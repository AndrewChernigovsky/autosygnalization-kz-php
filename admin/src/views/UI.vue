<script setup lang="ts">
import { ref } from 'vue';
import Header from '../components/layout/Header/Header.vue';
import MyInput from '../components/UI/MyInput.vue';

const inputValue = ref('');
</script>

<template>
  <div class="my-page p-20">
    <Header />
    <section class="ui">
      <h1>UI</h1>
      <label>
        <MyInput type="file" variant="primary" v-model="inputValue" />
      </label>
    </section>
  </div>
</template>

<style scoped>
label {
  display: block;
  width: 50px;
  height: 50px;
  border-radius: 30%;
  padding: 5px;
  background-color: #ffffff;

  & .my-input-wrapper.file {
    border-radius: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>
