<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';
import MyBtn from '../components/UI/MyBtn.vue';
import MyQuill from '../components/UI/MyQuill.vue';

// --- INTERFACES ---

interface AdvantageItem {
  advantage_id: number;
  content: string | null;
  image_path: string | null;
  position: number;
}

interface NewAdvantageSlot {
  tempId: number;
  file: File | null;
  preview: string | null;
  content: string;
}

interface VideoSource {
  source_id: number;
  src_path: string;
  src_type: string;
}

interface VideoItem {
  video_id: number;
  title: string | null;
  title_icon: string | null;
  video_poster: string | null;
  video_src_mob: string | null;
  position: number;
  sources: VideoSource[];
}

// --- STATE ---

// General
const openAccordion = ref<string | null>(null);
const fileInputs = ref<Record<string, HTMLInputElement | null>>({});

// Advantages State
const advantageItems = ref<AdvantageItem[]>([]);
const newAdvantageSlots = ref<NewAdvantageSlot[]>([]);
const isLoadingAdvantages = ref(false);
const errorAdvantages = ref<string | null>(null);
const advantageImagePreviews = ref<Record<number, string>>({});
const draggingAdvantageItem = ref<number | null>(null);

// Video State
const videoItems = ref<VideoItem[]>([]);
const originalVideoItem = ref<VideoItem | null>(null);
const isLoadingVideos = ref(false);
const errorVideos = ref<string | null>(null);
const videoPreviews = ref<Record<string, string | null>>({}); // Для превью

// --- API ---

const ADVANTAGES_API_URL = '/server/php/admin/api/advantage/advantage.php';
const VIDEOS_API_URL =
  '/server/php/admin/api/advantage-video/advantage-video.php';

// --- DATA FETCHING ---

const getAdvantageData = async () => {
  isLoadingAdvantages.value = true;
  errorAdvantages.value = null;
  try {
    const response = await fetchWithCors(ADVANTAGES_API_URL);
    if (response.success) {
      advantageItems.value = response.data.sort(
        (a: AdvantageItem, b: AdvantageItem) => a.position - b.position
      );
      newAdvantageSlots.value = [];
    } else {
      throw new Error(response.error || 'Не удалось загрузить преимущества');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    errorAdvantages.value = errorMessage;
    Swal.fire(
      'Ошибка',
      `Не удалось загрузить преимущества: ${errorMessage}`,
      'error'
    );
  } finally {
    isLoadingAdvantages.value = false;
  }
};

const getVideosData = async () => {
  isLoadingVideos.value = true;
  errorVideos.value = null;
  try {
    const response = await fetchWithCors(VIDEOS_API_URL);
    if (response.success) {
      videoItems.value = response.data.sort(
        (a: VideoItem, b: VideoItem) => a.position - b.position
      );
      if (videoItems.value.length > 0) {
        originalVideoItem.value = JSON.parse(
          JSON.stringify(videoItems.value[0])
        );
      } else {
        // Создаем заглушку, если видео не существует
        const placeholderVideo: VideoItem = {
          video_id: 0, // Используем 0 как признак нового видео
          title: '',
          title_icon: null,
          video_poster: null,
          video_src_mob: null,
          position: 1,
          sources: [],
        };
        videoItems.value.push(placeholderVideo);
        originalVideoItem.value = JSON.parse(JSON.stringify(placeholderVideo));
      }
    } else {
      throw new Error(response.error || 'Не удалось загрузить данные видео');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    errorVideos.value = errorMessage;
    Swal.fire('Ошибка', `Не удалось загрузить видео: ${errorMessage}`, 'error');
  } finally {
    isLoadingVideos.value = false;
  }
};

// --- ADVANTAGES LOGIC ---

const handleAdvantageCreate = async (
  event: Event,
  slot?: NewAdvantageSlot,
  slotIndex?: number
) => {
  const form = event.target as HTMLFormElement;
  const formData = new FormData(form);

  const content =
    (form.querySelector('.ql-editor') as HTMLElement)?.innerHTML || '';
  if (!content.replace(/<(.|\\n)*?>/g, '').trim()) {
    Swal.fire('Ошибка', 'Содержимое не может быть пустым.', 'error');
    return;
  }
  formData.append('content', content);

  if (!slot || !slot.file) {
    Swal.fire('Ошибка', 'Необходимо выбрать изображение.', 'error');
    return;
  }
  formData.append('image', slot.file);

  try {
    const response = await fetchWithCors(ADVANTAGES_API_URL, {
      method: 'POST',
      body: formData,
    });

    if (response.success && response.data) {
      advantageItems.value.push(response.data);
      Swal.fire('Создано!', 'Новый элемент успешно добавлен.', 'success');
      if (slotIndex !== undefined) {
        newAdvantageSlots.value.splice(slotIndex, 1);
      }
    } else {
      throw new Error(response.error || 'Ошибка при создании элемента');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    Swal.fire('Ошибка', errorMessage, 'error');
  }
};

const handleAdvantageUpdate = async (event: Event, item: AdvantageItem) => {
  const form = event.target as HTMLFormElement;
  const formData = new FormData(form);
  formData.append('advantage_id', item.advantage_id.toString());

  const inputFile = form.querySelector(
    'input[type="file"]'
  ) as HTMLInputElement;
  const hasNewFile = inputFile?.files?.[0];

  if (!item.image_path && !hasNewFile) {
    Swal.fire('Ошибка', 'Для этого элемента необходимо изображение.', 'error');
    return;
  }

  const editorContent = (form.querySelector('.ql-editor') as HTMLElement)
    ?.innerHTML;
  if (editorContent !== undefined) {
    formData.append('content', editorContent);
  }

  if (item.image_path === null) {
    formData.append('remove_image', '1');
  }

  try {
    const response = await fetchWithCors(ADVANTAGES_API_URL, {
      method: 'POST',
      body: formData,
    });

    if (response.success) {
      if (response.data.image_path) {
        item.image_path = response.data.image_path;
        delete advantageImagePreviews.value[item.advantage_id];
      }
      Swal.fire('Сохранено!', 'Данные успешно обновлены.', 'success');
    } else {
      throw new Error(response.error || 'Ошибка при сохранении');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    Swal.fire('Ошибка', errorMessage, 'error');
  }
};

const handleAdvantageDelete = async (id: number) => {
  const result = await Swal.fire({
    title: 'Вы уверены?',
    text: 'Вы не сможете восстановить этот элемент!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  });

  if (result.isConfirmed) {
    try {
      const response = await fetchWithCors(`${ADVANTAGES_API_URL}?id=${id}`, {
        method: 'DELETE',
      });

      if (response.success) {
        advantageItems.value = advantageItems.value.filter(
          (item) => item.advantage_id !== id
        );
        Swal.fire('Удалено!', 'Элемент был успешно удален.', 'success');
      } else {
        throw new Error(response.error || 'Ошибка при удалении');
      }
    } catch (err) {
      const errorMessage =
        err instanceof Error ? err.message : 'Неизвестная ошибка';
      Swal.fire('Ошибка', errorMessage, 'error');
    }
  }
};

const handleAdvantageUpdatePositions = async (
  updatedGroup: AdvantageItem[]
) => {
  const itemsToUpdate = updatedGroup.map((item, index) => ({
    advantage_id: item.advantage_id,
    position: index + 1,
  }));

  try {
    const response = await fetchWithCors(ADVANTAGES_API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'update_positions',
        items: itemsToUpdate,
      }),
    });
    if (response.success) {
      Swal.fire({
        toast: true,
        position: 'top',
        icon: 'success',
        title: 'Порядок изменен',
        showConfirmButton: false,
        timer: 1500,
      });
    } else {
      throw new Error(response.error || 'Не удалось обновить порядок');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    Swal.fire(
      'Ошибка',
      `Не удалось обновить порядок: ${errorMessage}`,
      'error'
    );
  }
};

const onAdvantageDragStart = (id: number) => {
  draggingAdvantageItem.value = id;
};

const onAdvantageDrop = (targetId: number) => {
  if (draggingAdvantageItem.value === null) return;

  const group = advantageItems.value;
  const fromIndex = group.findIndex(
    (it) => it.advantage_id === draggingAdvantageItem.value
  );
  const toIndex = group.findIndex((it) => it.advantage_id === targetId);

  if (fromIndex !== -1 && toIndex !== -1) {
    const [movedItem] = group.splice(fromIndex, 1);
    group.splice(toIndex, 0, movedItem);
    group.forEach((item, index) => {
      item.position = index + 1;
    });
    handleAdvantageUpdatePositions(group);
  }
  draggingAdvantageItem.value = null;
};

const addNewAdvantageSlot = () => {
  newAdvantageSlots.value.push({
    tempId: Date.now(),
    file: null,
    preview: null,
    content: '<p></p>',
  });
};

const removeNewAdvantageSlot = (index: number) => {
  newAdvantageSlots.value.splice(index, 1);
};

const clearNewAdvantageImageInSlot = (slot: NewAdvantageSlot) => {
  slot.file = null;
  slot.preview = null;
  const inputKey = `new-advantage-${slot.tempId}`;
  if (fileInputs.value[inputKey]) {
    fileInputs.value[inputKey]!.value = '';
  }
  Swal.fire({
    toast: true,
    position: 'top',
    icon: 'success',
    title: 'Картинка удалена',
    showConfirmButton: false,
    timer: 1500,
  });
};

const clearExistingAdvantageImage = (item: AdvantageItem) => {
  item.image_path = null;
  delete advantageImagePreviews.value[item.advantage_id];
  const inputKey = `existing-advantage-${item.advantage_id}`;
  if (fileInputs.value[inputKey]) {
    fileInputs.value[inputKey]!.value = '';
  }
  Swal.fire({
    toast: true,
    position: 'top',
    icon: 'success',
    title: 'Картинка удалена, добавьте новую',
    showConfirmButton: false,
    timer: 1500,
  });
};

const onAdvantageFileChange = (event: Event, itemId: number) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    advantageImagePreviews.value[itemId] = URL.createObjectURL(input.files[0]);
  } else {
    delete advantageImagePreviews.value[itemId];
  }
};

const onNewAdvantageFileChangeInSlot = (
  event: Event,
  slot: NewAdvantageSlot
) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    slot.file = input.files[0];
    slot.preview = URL.createObjectURL(input.files[0]);
  } else {
    slot.file = null;
    slot.preview = null;
  }
};

// --- VIDEOS LOGIC ---

const generateSuccessMessage = (parts: string[]): string => {
  if (parts.length === 0) {
    return 'Нет изменений для сохранения.';
  }
  const formattedParts = parts.map((p, i) => (i === 0 ? p : p.toLowerCase()));
  if (formattedParts.length === 1) {
    const part = formattedParts[0];
    if (part === 'Иконка') return 'Иконка успешно обновлена.';
    if (part === 'Основное видео') return 'Основное видео успешно обновлено.';
    return `${part} успешно обновлен.`; // Заголовок
  }
  const lastPart = formattedParts.pop();
  return `${formattedParts.join(', ')} и ${lastPart} успешно обновлены.`;
};

const handleVideoUpdate = async (event: Event, video: VideoItem) => {
  const form = event.target as HTMLFormElement;
  const formData = new FormData();
  const updatedParts: string[] = [];

  const originalVideo = originalVideoItem.value;

  if (!originalVideo) {
    Swal.fire(
      'Ошибка',
      'Не удалось найти исходные данные для сравнения.',
      'error'
    );
    return;
  }

  // 1. Проверка изменения заголовка
  if (video.title !== (originalVideo.title || '')) {
    formData.append('title', video.title || '');
    updatedParts.push('Заголовок');
  }

  // 2. Проверка изменения иконки
  const iconInput = form.querySelector(
    'input[name="title_icon"]'
  ) as HTMLInputElement;
  if (iconInput && iconInput.files && iconInput.files.length > 0) {
    formData.append('title_icon', iconInput.files[0]);
    if (!updatedParts.includes('Иконка')) updatedParts.push('Иконка');
  } else if (video.title_icon === null && originalVideo.title_icon !== null) {
    formData.append('remove_title_icon', '1');
    if (!updatedParts.includes('Иконка')) updatedParts.push('Иконка');
  }

  // 3. Проверка изменения основного видео
  const videoInput = form.querySelector(
    'input[name="main_video"]'
  ) as HTMLInputElement;
  if (videoInput && videoInput.files && videoInput.files.length > 0) {
    formData.append('main_video', videoInput.files[0]);
    updatedParts.push('Основное видео');
  } else if (
    video.video_poster === null &&
    originalVideo.video_poster !== null
  ) {
    formData.append('remove_main_video', '1');
    updatedParts.push('Основное видео');
  }

  if (updatedParts.length === 0) {
    Swal.fire({
      toast: true,
      position: 'top',
      icon: 'info',
      title: 'Нет изменений для сохранения',
      showConfirmButton: false,
      timer: 2000,
    });
    return;
  }

  const isCreating = video.video_id === 0;
  formData.append('video_id', video.video_id.toString());

  try {
    isLoadingVideos.value = true;
    const response = await fetchWithCors(VIDEOS_API_URL, {
      method: 'POST',
      body: formData,
    });
    if (response.success) {
      if (isCreating) {
        // Заменяем заглушку на реальные данные с сервера
        const createdVideo = response.data;
        const index = videoItems.value.findIndex((v) => v.video_id === 0);
        if (index !== -1) {
          videoItems.value.splice(index, 1, createdVideo);
        }
        originalVideoItem.value = JSON.parse(JSON.stringify(createdVideo));
        Swal.fire('Создано!', 'Видео-блок успешно создан.', 'success');
      } else {
        const successMessage = generateSuccessMessage(updatedParts);
        Swal.fire('Сохранено!', successMessage, 'success');
        await getVideosData(); // Перезагружаем для синхронизации
      }
      videoPreviews.value = {};
    } else {
      throw new Error(response.error || 'Ошибка при обновлении видео-блока');
    }
  } catch (err) {
    const errorMessage =
      err instanceof Error ? err.message : 'Неизвестная ошибка';
    Swal.fire('Ошибка', errorMessage, 'error');
    await getVideosData();
  } finally {
    isLoadingVideos.value = false;
  }
};

const onFileChange = (event: Event, type: 'icon' | 'video') => {
  const input = event.target as HTMLInputElement;
  const file = input.files?.[0];
  if (!file) {
    videoPreviews.value[type] = null;
    return;
  }

  if (type === 'icon') {
    videoPreviews.value.icon = URL.createObjectURL(file);
  } else if (type === 'video') {
    // Создаем превью постера из видео
    const videoURL = URL.createObjectURL(file);
    const videoElement = document.createElement('video');
    videoElement.src = videoURL;
    videoElement.currentTime = 1; // Берем кадр с 1 секунды
    videoElement.addEventListener('loadeddata', () => {
      const canvas = document.createElement('canvas');
      canvas.width = videoElement.videoWidth;
      canvas.height = videoElement.videoHeight;
      const ctx = canvas.getContext('2d');
      if (ctx) {
        ctx.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
        videoPreviews.value.video = canvas.toDataURL('image/jpeg');
      }
      URL.revokeObjectURL(videoURL);
    });
  }
};

const clearVideoFile = (type: 'icon' | 'video') => {
  if (videoItems.value.length === 0) return;

  const currentVideo = videoItems.value[0];
  let message = '';

  if (type === 'icon') {
    currentVideo.title_icon = null;
    message = 'Иконка будет удалена после сохранения';
  } else if (type === 'video') {
    // Помечаем видео на удаление
    currentVideo.video_poster = null;
    currentVideo.video_src_mob = null;
    currentVideo.sources = [];
    message = 'Видео будет удалено после сохранения';
  }

  // Сбрасываем превью
  videoPreviews.value[type] = null;

  // Сбрасываем значение в инпуте
  const inputId = type === 'icon' ? 'title-icon-input' : 'main-video-input';
  const input = document.getElementById(inputId) as HTMLInputElement;
  if (input) {
    input.value = '';
  }
  Swal.fire({
    toast: true,
    position: 'top',
    icon: 'info',
    title: message,
    showConfirmButton: false,
    timer: 2500,
  });
};

// --- GENERAL LOGIC ---

const toggleAccordion = (section: string) => {
  openAccordion.value = openAccordion.value === section ? null : section;
};

onMounted(() => {
  getAdvantageData();
  getVideosData();
});

// Transition Hooks
const beforeEnter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = '0';
  htmlEl.style.paddingTop = '0';
  htmlEl.style.paddingBottom = '0';
  htmlEl.style.opacity = '0';
};

const enter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = `${htmlEl.scrollHeight}px`;
  htmlEl.style.paddingTop = '1.5rem';
  htmlEl.style.paddingBottom = '1.5rem';
  htmlEl.style.opacity = '1';
};

const afterEnter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = '';
};

const beforeLeave = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = `${htmlEl.scrollHeight}px`;
};

const leave = (el: Element) => {
  const htmlEl = el as HTMLElement;
  getComputedStyle(htmlEl).height;
  requestAnimationFrame(() => {
    htmlEl.style.height = '0';
    htmlEl.style.paddingTop = '0';
    htmlEl.style.paddingBottom = '0';
    htmlEl.style.opacity = '0';
  });
};
</script>

<template>
  <div class="container-advan">
    <h1 class="main-title">Управление секцией 'Качество и преимущества'</h1>
    <div v-if="isLoadingAdvantages || isLoadingVideos" class="loading-overlay">
      <div class="spinner"></div>
    </div>
    <div v-else-if="errorAdvantages || errorVideos" class="error-message">
      <p>{{ errorAdvantages || errorVideos }}</p>
      <button @click="getAdvantageData">Попробовать снова</button>
    </div>
    <div v-else class="accordion">
      <!-- Секция Видео -->
      <div class="accordion-item">
        <button class="accordion-header" @click="toggleAccordion('videos')">
          <span>Управление видео</span>
          <span
            class="accordion-arrow"
            :class="{ 'is-open': openAccordion === 'videos' }"
          ></span>
        </button>
        <Transition
          name="accordion-transition"
          @before-enter="beforeEnter"
          @enter="enter"
          @after-enter="afterEnter"
          @before-leave="beforeLeave"
          @leave="leave"
        >
          <div v-if="openAccordion === 'videos'" class="accordion-content">
            <div class="video-list">
              <form
                v-for="video in videoItems"
                :key="video.video_id"
                class="form-group"
                @submit.prevent="handleVideoUpdate($event, video)"
              >
                <div class="content-wrapper">
                  <div class="form-layout">
                    <!-- Левая колонка -->
                    <div class="form-column">
                      <label>Заголовок видео-блока:</label>
                      <input
                        type="text"
                        name="title"
                        v-model="video.title"
                        placeholder="Например, Auto Security - Партнер Starline"
                      />

                      <label>Иконка заголовка (PNG/SVG):</label>
                      <p class="input-note">
                        До 5МБ, ~128x128px. Форматы: PNG, SVG, JPG.
                      </p>
                      <input
                        type="file"
                        name="title_icon"
                        class="hidden-file-input"
                        id="title-icon-input"
                        accept="image/png, image/svg+xml, image/jpeg"
                        @change="onFileChange($event, 'icon')"
                      />
                      <label
                        for="title-icon-input"
                        class="image-uploader image-uploader--small"
                      >
                        <div
                          v-if="videoPreviews.icon || video.title_icon"
                          class="image-preview-wrapper"
                        >
                          <img
                            :src="videoPreviews.icon || video.title_icon || ''"
                            class="image-preview"
                          />
                          <button
                            type="button"
                            class="btn-remove-image"
                            @click.prevent="clearVideoFile('icon')"
                          >
                            ×
                          </button>
                        </div>
                        <div v-else class="image-uploader-placeholder">
                          <span>+</span>
                        </div>
                      </label>

                      <label>Основное видео (для конвертации):</label>
                      <p class="input-note">
                        До 25МБ, ~1920x1080px. Форматы только MP4.
                      </p>
                      <input
                        type="file"
                        name="main_video"
                        class="hidden-file-input"
                        id="main-video-input"
                        accept="video/mp4"
                        @change="onFileChange($event, 'video')"
                      />
                      <label for="main-video-input" class="image-uploader">
                        <div
                          v-if="videoPreviews.video || video.video_poster"
                          class="image-preview-wrapper"
                        >
                          <img
                            :src="
                              videoPreviews.video || video.video_poster || ''
                            "
                            class="image-preview"
                          />
                          <button
                            type="button"
                            class="btn-remove-image"
                            @click.prevent="clearVideoFile('video')"
                          >
                            ×
                          </button>
                        </div>
                        <div v-else class="image-uploader-placeholder">
                          <span>+</span>
                        </div>
                      </label>
                    </div>
                    <!-- Правая колонка -->
                    <div class="form-column form-column--grow">
                      <div class="video-previews">
                        <div class="video-preview-item">
                          <label>Превью для десктопа:</label>
                          <video
                            v-if="video.sources && video.sources.length > 0"
                            :key="video.sources[0].src_path"
                            controls
                            muted
                            playsinline
                          >
                            <source
                              v-for="source in video.sources"
                              :key="source.source_id"
                              :src="source.src_path"
                              :type="source.src_type"
                            />
                            Ваш браузер не поддерживает тэг video.
                          </video>
                          <div v-else class="video-placeholder">
                            <span>Нет видео</span>
                          </div>
                        </div>
                      </div>
                      <p class="source-note">
                        Источники для десктопа (WebM и MP4) и мобильной версии
                        будут автоматически созданы из основного загруженного
                        видео.
                      </p>
                    </div>
                  </div>

                  <div class="actions">
                    <MyBtn variant="secondary" type="submit" class="btn-save">
                      Сохранить изменения
                    </MyBtn>
                  </div>
                </div>
              </form>
              <div class="add-slot-wrapper">
                <p class="form-note">
                  Так как на сайте используется только один видео-блок, вы
                  можете только редактировать его.
                </p>
              </div>
            </div>
          </div>
        </Transition>
      </div>

      <!-- Секция Преимуществ -->
      <div class="accordion-item">
        <button class="accordion-header" @click="toggleAccordion('advantages')">
          <span>Список преимуществ</span>
          <span
            class="accordion-arrow"
            :class="{ 'is-open': openAccordion === 'advantages' }"
          ></span>
        </button>
        <Transition
          name="accordion-transition"
          @before-enter="beforeEnter"
          @enter="enter"
          @after-enter="afterEnter"
          @before-leave="beforeLeave"
          @leave="leave"
        >
          <div v-if="openAccordion === 'advantages'" class="accordion-content">
            <div class="advantages-list">
              <!-- Рендеринг списка -->
              <form
                v-for="item in advantageItems"
                :key="item.advantage_id"
                class="form-group draggable"
                draggable="true"
                @dragstart="onAdvantageDragStart(item.advantage_id)"
                @dragover.prevent
                @drop="onAdvantageDrop(item.advantage_id)"
                @submit.prevent="handleAdvantageUpdate($event, item)"
              >
                <div class="drag-handle">⠿</div>
                <div class="content-wrapper">
                  <div class="form-layout">
                    <div class="form-column">
                      <label>Изображение:</label>
                      <div class="image-uploader">
                        <input
                          type="file"
                          name="image"
                          accept="image/*"
                          class="hidden-file-input"
                          :id="`file-input-existing-advantage-${item.advantage_id}`"
                          :ref="
                            (el) =>
                              (fileInputs[
                                `existing-advantage-${item.advantage_id}`
                              ] = el as HTMLInputElement)
                          "
                          @change="
                            onAdvantageFileChange($event, item.advantage_id)
                          "
                        />
                        <div
                          v-if="
                            advantageImagePreviews[item.advantage_id] ||
                            item.image_path
                          "
                          class="image-preview-wrapper"
                        >
                          <img
                            :src="
                              advantageImagePreviews[item.advantage_id] ||
                              item.image_path ||
                              ''
                            "
                            alt="preview"
                            class="image-preview"
                          />
                          <button
                            type="button"
                            class="btn-remove-image"
                            @click="clearExistingAdvantageImage(item)"
                          >
                            ×
                          </button>
                        </div>
                        <label
                          v-else
                          :for="`file-input-existing-advantage-${item.advantage_id}`"
                          class="image-uploader-placeholder"
                        >
                          <span>+</span>
                        </label>
                      </div>
                    </div>
                    <div class="form-column form-column--grow">
                      <label>Описание:</label>
                      <MyQuill v-model:content="item.content" />
                    </div>
                  </div>

                  <div class="actions">
                    <MyBtn variant="secondary" type="submit" class="btn-save">
                      Сохранить
                    </MyBtn>
                    <MyBtn
                      variant="primary"
                      type="button"
                      @click="handleAdvantageDelete(item.advantage_id)"
                      class="btn-delete"
                    >
                      Удалить
                    </MyBtn>
                  </div>
                </div>
              </form>

              <!-- Создание нового преимущества -->
              <form
                v-for="(slot, index) in newAdvantageSlots"
                :key="slot.tempId"
                class="form-add"
                @submit.prevent="handleAdvantageCreate($event, slot, index)"
              >
                <div class="content-wrapper">
                  <div class="form-layout">
                    <div class="form-column">
                      <label>Новое изображение:</label>
                      <div class="image-uploader">
                        <input
                          type="file"
                          name="image"
                          accept="image/*"
                          class="hidden-file-input"
                          :id="`file-input-new-advantage-${slot.tempId}`"
                          :ref="
                            (el) =>
                              (fileInputs[`new-advantage-${slot.tempId}`] =
                                el as HTMLInputElement)
                          "
                          @change="onNewAdvantageFileChangeInSlot($event, slot)"
                        />
                        <div v-if="slot.preview" class="image-preview-wrapper">
                          <img
                            :src="slot.preview"
                            alt="preview"
                            class="image-preview"
                          />
                          <button
                            type="button"
                            class="btn-remove-image"
                            @click="clearNewAdvantageImageInSlot(slot)"
                          >
                            ×
                          </button>
                        </div>
                        <label
                          v-else
                          :for="`file-input-new-advantage-${slot.tempId}`"
                          class="image-uploader-placeholder"
                        >
                          <span>+</span>
                        </label>
                      </div>
                    </div>
                    <div class="form-column form-column--grow">
                      <label>Описание:</label>
                      <MyQuill v-model:content="slot.content" />
                    </div>
                  </div>

                  <div class="actions">
                    <MyBtn variant="secondary" type="submit" class="btn-save"
                      >Сохранить</MyBtn
                    >
                    <MyBtn
                      variant="primary"
                      type="button"
                      @click="removeNewAdvantageSlot(index)"
                      class="btn-delete"
                    >
                      Удалить слот
                    </MyBtn>
                  </div>
                </div>
              </form>

              <div class="add-slot-wrapper">
                <MyBtn
                  variant="secondary"
                  type="button"
                  class="btn-add btn-add-slot"
                  @click="addNewAdvantageSlot"
                >
                  Добавить преимущество
                </MyBtn>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container-advan-advan {
  background-color: #2d2d2d;
  color: #e0e0e0;
  padding: 2rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica,
    Arial, sans-serif;
  min-height: 100vh;
  width: 100%;
}
.main-title {
  font-size: 1.8rem;
  font-weight: 600;
  color: #fff;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #444;
}
.loading-overlay {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
.spinner {
  border: 4px solid #444;
  border-top: 4px solid #007bff;
  border-radius: 50%;
  width: 50px;
  height: 50px;
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
  color: #ff6b6b;
  text-align: center;
}
.error-message button {
  margin-top: 1rem;
}
.advantages-list,
.video-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.form-group {
  margin-bottom: 0;
  padding: 1.5rem;
  border: 1px solid #444;
  border-radius: 8px;
  background-color: black;
}
.form-add {
  margin-top: 0;
  padding: 1.5rem;
  border: 2px dashed #555;
  border-radius: 8px;
}
label {
  display: block;
  margin-bottom: 0.75rem;
  font-weight: 500;
  color: #ccc;
}
input,
textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #555;
  border-radius: 4px;
  background-color: #2c2c2c;
  color: #e0e0e0;
  transition: border-color 0.3s, box-shadow 0.3s;
  margin-bottom: 1rem;
}
input:disabled {
  background-color: black;
  color: #888;
}
input:focus,
textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
}
.actions {
  margin-top: 1.5rem;
  display: flex;
  gap: 1rem;
}
.btn-save,
.btn-delete,
.btn-add {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  color: white;
  cursor: pointer;
  font-weight: bold;
  transition: transform 0.2s, filter 0.2s;
}
.btn-save:disabled,
.btn-add-link:disabled {
  background-image: none;
  background-color: #555;
  cursor: not-allowed;
  transform: none;
  filter: none;
}
.btn-save:hover,
.btn-delete:hover,
.btn-add:hover {
  transform: translateY(-2px);
  filter: brightness(1.1);
}

.btn-add {
  margin-top: 1rem;
  margin-bottom: 1rem;
}
.add-slot-wrapper {
  text-align: center;
  margin-top: 1rem;
}
.btn-add-slot {
  padding: 0.8rem 2rem;
  font-size: 1rem;
}
.draggable {
  cursor: grab;
  position: relative;
  display: flex;
  align-items: flex-start;
  gap: 15px;
}
.draggable:active {
  cursor: grabbing;
}
.drag-handle {
  font-size: 24px;
  color: #777;
  padding-top: 2.5rem;
  transition: color 0.3s;
}
.draggable:hover .drag-handle {
  color: #ccc;
}
.content-wrapper {
  flex-grow: 1;
}

.accordion {
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #444;
}
.accordion-item {
  border-bottom: 1px solid #444;
}
.accordion-item:last-child {
  border-bottom: none;
}
.accordion-header {
  width: 100%;
  background-color: black;
  border: none;
  padding: 1rem 1.5rem;
  text-align: left;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 1.2rem;
  font-weight: 600;
  color: #fff;
  transition: background-color 0.3s;
}
.accordion-header:hover {
  background-color: #4a4a4a;
}
.accordion-arrow {
  width: 10px;
  height: 10px;
  border-right: 2px solid #ccc;
  border-bottom: 2px solid #ccc;
  transform: rotate(45deg);
  transition: transform 0.3s;
}
.accordion-arrow.is-open {
  transform: translateY(2px) rotate(-135deg);
}
.accordion-content {
  background-color: black;
  overflow: hidden;
  transition: height 0.4s ease-out, padding 0.4s ease-out, opacity 0.4s ease-out;
  padding-left: 1.5rem;
  padding-right: 1.5rem;
}

.form-layout {
  display: flex;
  gap: 1.5rem;
  align-items: flex-start;
}
.form-column {
  display: flex;
  flex-direction: column;
}
.form-column--grow {
  flex: 1;
  min-width: 0;
}
.image-uploader {
  position: relative;
  width: 150px;
  height: 150px;
  cursor: pointer;
}
.image-uploader--small {
  height: 50px;
}
.image-uploader-placeholder {
  width: 100%;
  height: 100%;
  border: 2px dashed #555;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  background-color: black;
  transition: background-color 0.3s, border-color 0.3s;
}
.image-uploader-placeholder:hover {
  background-color: #4a4a4a;
  border-color: #777;
}
.image-uploader-placeholder span {
  font-size: 48px;
  color: #777;
  transition: color 0.3s;
}
.image-uploader-placeholder:hover span {
  color: #ccc;
}

.hidden-file-input {
  display: none;
}

.image-preview-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
}

.image-preview {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 8px;
  border: 1px solid #555;
}

.btn-remove-image {
  position: absolute;
  top: -10px;
  right: -10px;
  width: 28px;
  height: 28px;
  background-color: #dc3545;
  color: white;
  border: 2px solid #2d2d2d;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  line-height: 1;
  cursor: pointer;
  transition: transform 0.2s, background-color 0.2s;
  padding: 0;
}
.btn-remove-image:hover {
  transform: scale(1.1);
  background-color: #c82333;
}

.source-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  background: #2c2c2c;
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1rem;
}
.source-item {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}
.source-type-input {
  flex-basis: 120px;
  flex-shrink: 0;
}
.btn-delete-link {
  background: #c82333;
  border: none;
  color: white;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  cursor: pointer;
}
.btn-add-link {
  background: #218838;
  border: none;
  color: white;
  border-radius: 4px;
  padding: 0.5rem;
  cursor: pointer;
  margin-top: 0.5rem;
}
.input-note {
  font-size: 0.8rem;
  color: #888;
  margin-top: -0.5rem;
  margin-bottom: 0.75rem;
}
.source-note {
  font-size: 0.8rem;
  color: #888;
  margin-top: 0.5rem;
}
.form-note {
  font-size: 0.9rem;
  color: #888;
  text-align: center;
}
.video-previews {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.video-preview-item video {
  width: 100%;
  border-radius: 8px;
  border: 1px solid #555;
  background-color: #2c2c2c;
  display: block;
}
.video-preview-item .video-placeholder {
  width: 100%;
  aspect-ratio: 16 / 9;
  border-radius: 8px;
  border: 2px dashed #555;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: black;
  color: #888;
}
:deep(.ql-toolbar) {
  background: black;
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
  border-color: #555;
  border-bottom: 0;
}
:deep(.ql-container-advan-advan.ql-snow) {
  border-color: #555;
  border-bottom-left-radius: 4px;
  border-bottom-right-radius: 4px;
  color: #e0e0e0;
}
:deep(.ql-editor) {
  min-height: 250px;
  background-color: #2c2c2c;
}
:deep(.ql-snow .ql-stroke) {
  stroke: #e0e0e0;
}
:deep(.ql-snow .ql-picker-label::before) {
  color: #e0e0e0;
}
:deep(.ql-snow .ql-picker-options) {
  background-color: black;
  border-color: #555;
  color: #e0e0e0;
}
:deep(.ql-snow .ql-picker-item:hover) {
  background-color: #4a4a4a;
}
:deep(.ql-snow .ql-picker-item.ql-selected) {
  background-color: #5a5a5a;
}
</style>
