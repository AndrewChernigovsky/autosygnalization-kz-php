<script setup lang="ts">
import { ref } from 'vue';
import Swal from 'sweetalert2';

interface Props {
  videoId: number | undefined;
}

interface Emits {
  (event: 'deleted', videoId: number): void;
  (event: 'status-update', status: string): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const isDeleting = ref(false);

function confirmDelete() {
  if (!props.videoId) return;

  Swal.fire({
    title: 'Вы уверены?',
    text: 'Вы не сможете отменить это действие!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  }).then((result) => {
    if (result.isConfirmed) {
      deleteVideo();
    }
  });
}

async function deleteVideo() {
  if (!props.videoId) return;

  isDeleting.value = true;
  emit('status-update', 'Удаление...');

  try {
    const response = await fetch('/server/php/admin/api/delete-video.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id: props.videoId }),
    });

    if (response.ok) {
      emit('deleted', props.videoId);
      emit('status-update', 'Видео успешно удалено');
    } else {
      const errorData = await response.json();
      emit('status-update', `Ошибка: ${errorData.error}`);
    }
  } catch (error: any) {
    emit('status-update', 'Ошибка соединения: ' + error.message);
  } finally {
    isDeleting.value = false;
  }
}
</script>

<template>
  <button
    v-if="props.videoId"
    @click="confirmDelete"
    class="delete-btn"
    :disabled="isDeleting"
  >
    {{ isDeleting ? 'Удаление...' : 'Удалить видео' }}
  </button>
</template>

<style scoped>
.delete-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background-color: #dc3545;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 5px 10px;
  cursor: pointer;
  z-index: 10;
}
.delete-btn:disabled {
  background-color: #ccc;
}
</style>
