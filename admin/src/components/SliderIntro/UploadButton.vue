<script setup lang="ts">
import { ref } from 'vue';
import Swal from 'sweetalert2';

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
const videoFileName = ref<string | null>(null);
const inputRef = ref<HTMLInputElement | null>(null);

// Обработка загрузки видеофайла
function handleVideoUpload(event: Event) {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const fileSize = file.size / 1024 / 1024; // в МБ
    const fileType = file.type;

    if (fileSize > 100) {
      Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: 'Размер файла не должен превышать 100 МБ.',
      });
      if (input) input.value = '';
      videoFileName.value = null;
      return;
    }

    if (!fileType.startsWith('video/')) {
      Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: 'Разрешен только видео формат.',
      });
      if (input) input.value = '';
      videoFileName.value = null;
      return;
    }

    videoFile.value = file;
    videoFileName.value = file.name;
    const preview = URL.createObjectURL(videoFile.value);
    emit('video-preview', preview);
    uploadVideo();
  } else {
    videoFileName.value = null;
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
  props.formData.delete('slide_id');

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
  if (inputRef.value) {
    inputRef.value.value = '';
  }
  videoFile.value = null;
  videoFileName.value = null;
}

// Экспортируем методы для родительского компонента
defineExpose({
  clearInput,
});
</script>

<template>
  <div class="upload-container">
    <label class="upload-label">
      <span class="upload-label-text">Выбрать видео</span>
      <input
        id="video-upload-input"
        ref="inputRef"
        type="file"
        accept="video/*"
        @change="handleVideoUpload"
        class="upload-input"
      />
    </label>
    <span v-if="videoFileName" class="file-name">{{ videoFileName }}</span>
  </div>
</template>

<style scoped lang="scss">
.upload-container {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.upload-label {
  display: inline-block;
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  text-align: center;
  margin-bottom: 10px;
}

.upload-input {
  display: none;
}

.file-name {
  margin-top: 5px;
  font-size: 14px;
  color: #333;
}
</style>
