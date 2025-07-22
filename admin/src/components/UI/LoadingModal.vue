<script setup lang="ts">
const props = defineProps<{
  isLoading: boolean;
  error: string | null;
  retry: () => Promise<void>;
}>();

const handleRetry = () => {
  props.retry();
};
</script>

<template>
  <div>
    <div v-if="props.isLoading" class="loading-overlay">
      <div class="spinner"></div>
    </div>

    <div v-else-if="props.error" class="error-message">
      {{ props.error }}
      <button @click="handleRetry" class="retry-btn">Попробовать снова</button>
    </div>
  </div>
</template>

<style scoped></style>
