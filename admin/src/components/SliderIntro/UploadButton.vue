<script setup lang="ts">
import { ref } from 'vue';

interface Props {
  slideId: number | undefined;
  title: string;
  advantages: string[];
  buttonText: string;
  buttonLink: string;
  formData: FormData;
}

interface Emits {
  (
    event: 'upload-success',
    data: { id: number; filename: string; path: string }
  ): void;
  (event: 'status-update', status: string): void;
  (event: 'progress-update', progress: number): void;
  (event: 'video-preview', preview: string): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const videoFile = ref<File | null>(null);

// Обработка загрузки видеофайла
function handleVideoUpload(event: Event) {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    videoFile.value = input.files[0];
    const preview = URL.createObjectURL(videoFile.value);
    emit('video-preview', preview);
    uploadVideo();
  }
}

// Отправка видео на сервер
function uploadVideo() {
  if (!videoFile.value) return;

  emit('status-update', 'Загрузка...');
  emit('progress-update', 0);

  // Очищаем предыдущие данные в formData, чтобы избежать дублирования
  props.formData.delete('video');
  props.formData.delete('title');
  props.formData.delete('advantages');
  props.formData.delete('button_text');
  props.formData.delete('button_link');

  // Добавляем новые данные
  if (props.slideId) {
    props.formData.append('slide_id', String(props.slideId));
  }
  props.formData.append('video', videoFile.value);
  props.formData.append('title', props.title);
  // Гарантируем, что advantages всегда будет корректным JSON-массивом
  const advantagesJson = JSON.stringify(
    props.advantages.length > 0 ? props.advantages : []
  );
  props.formData.append('advantages', advantagesJson);
  props.formData.append('button_text', props.buttonText);
  props.formData.append('button_link', props.buttonLink);

  const xhr = new XMLHttpRequest();
  xhr.open('POST', '/server/php/admin/api/upload-video.php', true);

  xhr.upload.onprogress = function (event) {
    if (event.lengthComputable) {
      const progress = Math.round((event.loaded * 100) / event.total);
      emit('progress-update', progress);
    }
  };

  xhr.onload = function () {
    console.log('Response status:', xhr.status);
    console.log('Response text:', xhr.responseText);

    if (xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        emit('upload-success', {
          id: response.id,
          filename: response.filename,
          path: response.path,
        });
        emit('status-update', 'Загрузка завершена. ID записи: ' + response.id);
      } catch (e: any) {
        console.error('JSON parse error:', e);
        emit('status-update', 'Ошибка обработки ответа сервера: ' + e.message);
      }
    } else {
      emit('status-update', 'Ошибка загрузки: ' + xhr.statusText);
    }
  };

  xhr.onerror = function () {
    emit('status-update', 'Ошибка соединения с сервером');
  };

  xhr.send(props.formData);
}

// Метод для очистки input file (вызывается извне)
function clearInput() {
  const input = document.getElementById('videoInput') as HTMLInputElement;
  if (input) input.value = '';
  videoFile.value = null;
}

// Экспортируем методы для родительского компонента
defineExpose({
  clearInput,
});

// Удаляю некорректный экспорт по умолчанию
// export default {
//   name: 'UploadButton'
// };
</script>

<template>
  <input
    id="videoInput"
    type="file"
    accept="video/*"
    @change="handleVideoUpload"
  />
</template>

<style scoped lang="scss">
input {
  padding: 8px;
  width: 100%;
  box-sizing: border-box;
}
</style>
