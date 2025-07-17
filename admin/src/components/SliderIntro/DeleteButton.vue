<script setup lang="ts">
import Swal from 'sweetalert2';

interface Props {
  videoId: number | null;
}

interface Emits {
  (event: 'deleted'): void;
  (event: 'status-update', status: string): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

// Удаление видео
function deleteVideo() {
  if (!props.videoId) {
    emit('status-update', 'Нет видео для удаления');
    return;
  }

  Swal.fire({
    title: 'Вы уверены?',
    text: 'Вы не сможете восстановить это видео!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  }).then(result => {
    if (result.isConfirmed) {
      emit('status-update', 'Удаление...');

      const xhr = new XMLHttpRequest();
      xhr.open(
        'DELETE',
        `/server/php/admin/api/delete-video.php?id=${props.videoId}`,
        true
      );

      xhr.onload = function () {
        console.log('Delete response status:', xhr.status);
        console.log('Delete response text:', xhr.responseText);

        if (xhr.status === 200) {
          try {
            const response = JSON.parse(xhr.responseText);
            emit('status-update', 'Видео успешно удалено');
            emit('deleted');
          } catch (e: any) {
            console.error('JSON parse error:', e);
            emit('status-update', 'Ошибка обработки ответа: ' + e.message);
          }
        } else {
          emit('status-update', 'Ошибка удаления: ' + xhr.statusText);
        }
      };

      xhr.onerror = function () {
        emit('status-update', 'Ошибка соединения с сервером');
      };

      xhr.send();
    }
  });
}
</script>

<template>
  <button v-if="videoId" @click="deleteVideo" class="delete-btn">
    Удалить видео
  </button>
</template>

<style scoped lang="scss">
.delete-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 3px;
  cursor: pointer;
  font-size: 12px;
}

.delete-btn:hover {
  background-color: #c82333;
}
</style>
