<template>
  <div class="loader-container">
    <div :class="['loader', size === 'small' ? 'loader-small' : '']"></div>
    <p v-if="message" class="loader-message">{{ message }}</p>
  </div>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    message?: string;
    size?: 'large' | 'small';
  }>(),
  {
    message: '',
    size: 'large',
  }
);
</script>

<script lang="ts">
export default {
  name: 'Loader',
};
</script>

<style scoped>
.loader-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 1rem;
}

.loader {
  border: 4px solid #555;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

.loader-small {
  border-width: 3px;
  width: 30px;
  height: 30px;
}

.loader-message {
  color: #f1f1f1;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
