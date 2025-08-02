<script setup lang="ts">
const emit = defineEmits(['close']);

const closeModal = () => {
  emit('close');
};
</script>

<template>
  <div class="modal-overlay" @click.self="closeModal">
    <div class="modal-container">
      <header class="modal-header">
        <slot name="header">
          <!-- Заголовок по умолчанию -->
        </slot>
        <button class="close-button" @click="closeModal">&times;</button>
      </header>
      <section class="modal-body">
        <slot name="body">
          <!-- Тело модального окна по умолчанию -->
        </slot>
      </section>
      <footer class="modal-footer">
        <slot name="footer">
          <!-- Футер по умолчанию -->
        </slot>
      </footer>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-container {
  background-color: #2c2c2e;
  color: #f0f0f0;
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
  min-width: 500px;
  max-width: 90%;
  display: flex;
  flex-direction: column;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  border-bottom: 1px solid #4b4b4b;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
}

.close-button {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #f0f0f0;
  cursor: pointer;
}

.modal-body {
  padding: 16px;
  overflow-y: auto;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding: 16px;
  border-top: 1px solid #4b4b4b;
}
</style>
