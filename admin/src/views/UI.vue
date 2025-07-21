<script setup lang="ts">
import MyBtn from '../components/UI/MyBtn.vue';
import MyInput from '../components/UI/MyInput.vue';
import { ref } from 'vue';

const inputValue = ref('');

const handleClick = (event: MouseEvent) => {
  console.log('Button clicked!', event);
};
</script>

<template>
  <div>
    <!-- Input компонент -->
    <MyInput
      v-model="inputValue"
      placeholder="Автосигнализации с автозапуском"
    />

    <!-- Кнопки -->
    <MyBtn @click="handleClick" />

    <MyBtn variant="secondary" @click="handleClick"> ОТМЕНИТЬ </MyBtn>
  </div>
</template>

<style scoped></style>
