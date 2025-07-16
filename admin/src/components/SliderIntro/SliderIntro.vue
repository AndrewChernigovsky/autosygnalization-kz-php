<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import UploadButton from './UploadButton.vue';
import DeleteButton from './DeleteButton.vue';
import { Navigation, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const videoPreview = ref<string | null>(null);
const uploadStatus = ref<string>('');
const uploadProgress = ref<number>(0);
const currentVideoId = ref<number | null>(null);
const currentSlideIndex = ref<number>(0);

const uploadButtonRef = ref<InstanceType<typeof UploadButton> | null>(null);
const formData = new FormData();

const title = ref<string>('');
const items = ref<
  {
    id?: number;
    poster: string;
    srcMob: string;
    src: string[];
    type: string[];
    title: string;
    advantages: string[];
    link: string;
    video_path?: string;
  }[]
>([]);

const advantages = reactive<string[]>([]);

function addAdvantage() {
  advantages.push('');
}

function removeAdvantage(index: number) {
  advantages.splice(index, 1);
}

const buttonText = ref<string>('Подробнее');
const buttonLink = ref<string>('#');

function handleUploadSuccess(data: {
  id: number;
  filename: string;
  path: string;
}) {
  currentVideoId.value = data.id;
  if (items.value[currentSlideIndex.value]) {
    items.value[currentSlideIndex.value].video_path = data.path;
    videoPreview.value = data.path;
  }
}

function handleStatusUpdate(status: string) {
  uploadStatus.value = status;
}

function handleProgressUpdate(progress: number) {
  uploadProgress.value = progress;
}

function handleVideoPreview(preview: string) {
  videoPreview.value = preview;
}

function handleVideoDeleted() {
  videoPreview.value = null;
  currentVideoId.value = null;
  uploadProgress.value = 0;
  if (items.value[currentSlideIndex.value]) {
    items.value[currentSlideIndex.value].video_path = '';
  }

  if (uploadButtonRef.value) {
    uploadButtonRef.value.clearInput();
  }
}

async function sendDataToServer() {
  if (!items.value[currentSlideIndex.value]?.id) return;
  const slideData = {
    id: items.value[currentSlideIndex.value].id,
    title: title.value,
    advantages: JSON.stringify(advantages),
    button_text: buttonText.value,
    button_link: buttonLink.value,
  };

  try {
    const response = await fetch('/server/php/admin/api/update-slide.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(slideData),
    });
    if (response.ok) {
      uploadStatus.value = 'Данные успешно сохранены';
    } else {
      uploadStatus.value = 'Ошибка сохранения данных';
    }
  } catch (error) {
    console.error('Ошибка отправки данных:', error);
    uploadStatus.value = 'Ошибка отправки данных';
  }
}

async function addSlide() {
  try {
    const response = await fetch('/server/php/admin/api/add-slide.php', {
      method: 'POST',
    });
    if (response.ok) {
      const newSlide = await response.json();
      items.value.push({
        id: newSlide.id,
        poster: '',
        srcMob: '',
        src: [],
        type: [],
        title: 'Новый слайд',
        advantages: [],
        link: '#',
        video_path: '',
      });
      uploadStatus.value = 'Новый слайд добавлен';
    } else {
      uploadStatus.value = 'Ошибка добавления слайда';
    }
  } catch (error) {
    console.error('Ошибка добавления слайда:', error);
    uploadStatus.value = 'Ошибка добавления слайда';
  }
}

async function removeSlide(id: number | undefined, index: number) {
  if (!id) return;
  try {
    const response = await fetch('/server/php/admin/api/delete-slide.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id }),
    });
    if (response.ok) {
      items.value.splice(index, 1);
      uploadStatus.value = 'Слайд удален';
    } else {
      uploadStatus.value = 'Ошибка удаления слайда';
    }
  } catch (error) {
    console.error('Ошибка удаления слайда:', error);
    uploadStatus.value = 'Ошибка удаления слайда';
  }
}

function loadSlideData(index: number) {
  if (items.value[index]) {
    title.value = items.value[index].title;
    advantages.splice(0, advantages.length, ...items.value[index].advantages);
    buttonLink.value = items.value[index].link;
    buttonText.value = 'Подробнее';
    videoPreview.value = items.value[index].video_path || null;
  }
}

function handleSlideChange(swiper: any) {
  currentSlideIndex.value = swiper.activeIndex;
  loadSlideData(currentSlideIndex.value);
}

onMounted(async () => {
  try {
    const response = await fetch('/server/php/api/get-slides.php');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    items.value = data.map((video: any) => ({
      id: video.id,
      poster: video.video_path || '',
      srcMob: video.video_path || '',
      src: [video.video_path || ''],
      type: ['video/mp4'],
      title: video.title || '',
      advantages: video.advantages || [],
      link: video.button_link || '#',
      video_path: video.video_path || '',
    }));
    if (items.value.length > 0) {
      loadSlideData(0);
    }
  } catch (error) {
    console.error('Ошибка загрузки данных видео:', error);
    uploadStatus.value = 'Ошибка загрузки данных видео';
  }
});
</script>

<template>
  <div class="slider-intro">
    <h1>Главный слайдер на главной странице</h1>

    <Swiper
      :modules="[Navigation, Pagination]"
      :slides-per-view="1"
      :space-between="30"
      :pagination="{ clickable: true }"
      :navigation="true"
      class="swiper-container"
      @slide-change="handleSlideChange"
    >
      <SwiperSlide v-for="(item, index) in items" :key="item.id || index">
        <div class="video-upload">
          <label for="videoInput"
            >Загрузить видео для слайда {{ index + 1 }}:</label
          >
          <div class="preview-video">
            <div v-if="videoPreview" class="video-preview">
              <video
                :src="videoPreview"
                controls
                width="300"
                height="auto"
              ></video>
              <DeleteButton
                :video-id="item.id || currentVideoId"
                @deleted="handleVideoDeleted"
                @status-update="handleStatusUpdate"
              />
            </div>
            <div v-else class="no-video">
              <div class="no-video-placeholder"></div>
            </div>
          </div>
          <UploadButton
            ref="uploadButtonRef"
            :slide-id="item.id"
            :title="title"
            :advantages="advantages"
            :button-text="buttonText"
            :button-link="buttonLink"
            :form-data="formData"
            @upload-success="handleUploadSuccess"
            @status-update="handleStatusUpdate"
            @progress-update="handleProgressUpdate"
            @video-preview="handleVideoPreview"
          />
          <div v-if="uploadStatus" class="upload-status">
            <p>{{ uploadStatus }}</p>
            <div
              class="progress-bar"
              v-if="uploadProgress > 0 && uploadProgress < 100"
            >
              <div
                class="progress"
                :style="{ width: uploadProgress + '%' }"
              ></div>
            </div>
          </div>
        </div>

        <div class="title-input">
          <label for="titleInput">Заголовок слайда {{ index + 1 }}:</label>
          <input
            id="titleInput"
            type="text"
            v-model="title"
            placeholder="Введите заголовок"
          />
        </div>

        <div class="advantages-list">
          <h3>Преимущества слайда {{ index + 1 }}:</h3>
          <div
            v-for="(advantage, advIndex) in advantages"
            :key="advIndex"
            class="advantage-item"
          >
            <input
              type="text"
              v-model="advantages[advIndex]"
              placeholder="Введите преимущество"
            />
            <button @click="removeAdvantage(advIndex)" class="remove-btn">
              Удалить
            </button>
          </div>
          <button @click="addAdvantage" class="add-btn">
            Добавить преимущество
          </button>
        </div>

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
        </div>

        <button @click="sendDataToServer" class="send-btn">
          Отправить данные для слайда {{ index + 1 }}
        </button>
        <button @click="removeSlide(item.id, index)" class="remove-slide-btn">
          Удалить слайд {{ index + 1 }}
        </button>
      </SwiperSlide>
    </Swiper>
    <button @click="addSlide" class="add-slide-btn">
      Добавить новый слайд
    </button>
  </div>
</template>

<style scoped lang="scss">
.slider-intro {
  padding: 20px;
  max-width: 800px;
  margin: 0 auto;
}

.swiper-container {
  width: 100%;
  height: auto;
  padding-bottom: 50px;
  position: relative;
}

.swiper-slide {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  width: 100%;
}

.video-upload {
  margin-bottom: 20px;
}

.preview-video {
  margin-bottom: 10px;
}

.video-preview {
  margin-top: 10px;
  position: relative;
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
.remove-btn,
.send-btn,
.add-slide-btn,
.remove-slide-btn {
  padding: 5px 10px;
  cursor: pointer;
  margin-right: 10px;
  margin-bottom: 10px;
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

.swiper-pagination {
  position: absolute;
  bottom: 20px;
  left: 0;
  width: 100%;
}

.swiper-button-prev,
.swiper-button-next {
  position: relative;
  top: auto;
  width: 40px;
  height: 40px;
  margin-top: 10px;
}
</style>
