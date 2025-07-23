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

<style scoped>
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.9);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  gap: 1rem;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #42b883;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.error-message {
  color: #dc3545;
  padding: 2rem;
  background: #f8d7da;
  border-radius: 8px;
  margin: 2rem 0;
  text-align: center;
}

.retry-btn {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background: #42b883;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}

.retry-btn:hover {
  background: #3aa876;
}
</style>
