<script setup lang="ts">
import { ref, reactive } from 'vue';

// Состояние для видеофайла
const videoFile = ref<File | null>(null);
const videoPreview = ref<string | null>(null);
const uploadStatus = ref<string>('');
const uploadProgress = ref<number>(0);

// Обработка загрузки видеофайла
function handleVideoUpload(event: Event) {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    videoFile.value = input.files[0];
    videoPreview.value = URL.createObjectURL(videoFile.value);
    uploadVideo();
  }
}

// Отправка видео на сервер
function uploadVideo() {
  if (!videoFile.value) return;

  uploadStatus.value = 'Загрузка...';
  uploadProgress.value = 0;

  const formData = new FormData();
  formData.append('video', videoFile.value);
  formData.append('title', title.value);
  formData.append('advantages', JSON.stringify(advantages));
  formData.append('button_text', buttonText.value);
  formData.append('button_link', buttonLink.value);

  const xhr = new XMLHttpRequest();
  xhr.open('POST', '/server/php/admin/api/upload-video.php', true);

  xhr.upload.onprogress = function (event) {
    if (event.lengthComputable) {
      uploadProgress.value = Math.round((event.loaded * 100) / event.total);
    }
  };

  xhr.onload = function () {
    console.log('Response status:', xhr.status);
    console.log('Response text:', xhr.responseText);

    if (xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        uploadStatus.value = 'Загрузка завершена. ID записи: ' + response.id;
      } catch (e: any) {
        console.error('JSON parse error:', e);
        uploadStatus.value = 'Ошибка обработки ответа сервера: ' + e.message;
      }
    } else {
      uploadStatus.value = 'Ошибка загрузки: ' + xhr.statusText;
    }
  };

  xhr.onerror = function () {
    uploadStatus.value = 'Ошибка соединения с сервером';
  };

  xhr.send(formData);
}

// Состояние для заголовка
const title = ref<string>('');

// Состояние для списка преимуществ
const advantages = reactive<string[]>([]);

function addAdvantage() {
  advantages.push('');
}

function removeAdvantage(index: number) {
  advantages.splice(index, 1);
}

// Состояние для кнопки "Подробнее"
const buttonText = ref<string>('Подробнее');
const buttonLink = ref<string>('#');
</script>

<template>
  <div class="slider-intro">
    <h1>Slider Intro</h1>

    <!-- Загрузка видео -->
    <div class="video-upload">
      <label for="videoInput">Загрузить видео:</label>
      <div class="preview-video">
        <div v-if="videoPreview" class="video-preview">
          <video :src="videoPreview" controls width="300" height="auto"></video>
        </div>
        <div v-else class="no-video">
          <div class="no-video-placeholder"></div>
        </div>
      </div>
      <input
        id="videoInput"
        type="file"
        accept="video/*"
        @change="handleVideoUpload"
      />
      <div v-if="uploadStatus" class="upload-status">
        <p>{{ uploadStatus }}</p>
        <div
          class="progress-bar"
          v-if="uploadProgress > 0 && uploadProgress < 100"
        >
          <div class="progress" :style="{ width: uploadProgress + '%' }"></div>
        </div>
      </div>
    </div>

    <!-- Поле для заголовка -->
    <div class="title-input">
      <label for="titleInput">Заголовок:</label>
      <input
        id="titleInput"
        type="text"
        v-model="title"
        placeholder="Введите заголовок"
      />
      <h2 v-if="title">Предпросмотр: {{ title }}</h2>
    </div>

    <!-- Список преимуществ -->
    <div class="advantages-list">
      <h3>Преимущества:</h3>
      <div
        v-for="(advantage, index) in advantages"
        :key="index"
        class="advantage-item"
      >
        <input
          type="text"
          v-model="advantages[index]"
          placeholder="Введите преимущество"
        />
        <button @click="removeAdvantage(index)" class="remove-btn">
          Удалить
        </button>
      </div>
      <button @click="addAdvantage" class="add-btn">
        Добавить преимущество
      </button>
    </div>

    <!-- Кнопка Подробнее -->
    <div class="button-config">
      <div>
        <label for="buttonText">Текст кнопки:</label>
        <input
          id="buttonText"
          type="text"
          v-model="buttonText"
          placeholder="Введите текст кнопки"
        />
      </div>
      <div>
        <label for="buttonLink">Ссылка кнопки:</label>
        <input
          id="buttonLink"
          type="text"
          v-model="buttonLink"
          placeholder="Введите ссылку"
        />
      </div>
      <a :href="buttonLink" class="details-btn">{{ buttonText }}</a>
    </div>
  </div>
</template>

<style scoped lang="scss">
.slider-intro {
  padding: 20px;
  max-width: 800px;
  margin: 0 auto;
}

.video-upload {
  margin-bottom: 20px;
}

.preview-video {
  margin-bottom: 10px;
}

.video-preview {
  margin-top: 10px;
}

.no-video {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 300px;
  height: 169px;
  background-color: #e0e0e0;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.no-video-placeholder {
  width: 100%;
  height: 100%;
  background-color: #808080;
}

.upload-status {
  margin-top: 10px;
}

.progress-bar {
  width: 100%;
  background-color: #e0e0e0;
  border-radius: 5px;
  overflow: hidden;
}

.progress {
  height: 20px;
  background-color: #007bff;
  transition: width 0.3s ease;
}

.title-input {
  margin-bottom: 20px;
}

.advantages-list {
  margin-bottom: 20px;
}

.advantage-item {
  display: flex;
  gap: 10px;
  margin-bottom: 10px;
}

.add-btn,
.remove-btn {
  padding: 5px 10px;
  cursor: pointer;
}

.button-config {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.details-btn {
  display: inline-block;
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  margin-top: 10px;
  text-align: center;
}

input {
  padding: 8px;
  width: 100%;
  box-sizing: border-box;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}
</style>
