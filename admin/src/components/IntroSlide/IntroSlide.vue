<script setup lang="ts">
import { onMounted, ref, watchEffect } from 'vue';
import { storeToRefs } from 'pinia';
import MyBtn from '../UI/MyBtn.vue';
import DraggableList from '../UI/DraggableList.vue';
import LinkSelector from '../UI/LinkSelector.vue';
import useIntroSlideStore from '../../stores/introSlideStore';
import Swal from 'sweetalert2';
import fetchWithCors from '../../utils/fetchWithCors';
import type { IntroSlideData } from './interfaces/introSlideData';
import type { LinkData } from '../../types/FooterLinks';

const openAccardion = ref<Record<number, boolean>>({});
const openAdvantageAccardion = ref<Record<number, boolean>>({});
const videoPreviews = ref<Record<number, { video?: string; poster?: string }>>(
  {}
);
const filesToUpload = ref<Record<number, { video?: File; poster?: File }>>({});
const fileInputRefs = ref<Record<string, HTMLInputElement | null>>({});
const filesToDelete = ref<
  Record<number, { video?: boolean; poster?: boolean }>
>({});
const editableItemData = ref<Record<number, IntroSlideData>>({});
const newSlide = ref<IntroSlideData | null>(null);
const isAddingNewSlide = ref(false);
const loadingAction = ref<'loading' | 'saving' | 'deleting' | 'reordering'>(
  'loading'
);

// Переменные для работы со ссылками
const allLinksData = ref<LinkData[]>([]);
const selectedLinks = ref<Record<number, LinkData | null>>({});
const linkSourceMode = ref<Record<number, 'custom' | 'existing'>>({});
const customLinks = ref<Record<number, string>>({});

// --- Helpers to normalize hrefs for phone/email ---
const normalizePhoneHref = (value: string): string => {
  if (!value) return '';
  const v = value.trim();
  if (v.toLowerCase().startsWith('tel:')) return v;
  // Keep leading + and digits only
  const raw = v.replace(/[^\d+]/g, '');
  const withPlus = raw.startsWith('+') ? raw : `+${raw.replace(/^\+/, '')}`;
  return `tel:${withPlus}`;
};

const normalizeEmailHref = (value: string): string => {
  if (!value) return '';
  const v = value.trim();
  if (v.toLowerCase().startsWith('mailto:')) return v;
  return `mailto:${v}`;
};

const detectTypeByValue = (value: string): 'phone' | 'email' | 'link' => {
  const v = value.trim().toLowerCase();
  if (v.includes('@') && !v.startsWith('http')) return 'email';
  // digits, spaces, dashes, plus -> likely phone
  if (/^[+\d][\d\s\-()]*$/.test(v)) return 'phone';
  return 'link';
};

const setFileInputRef = (el: any, itemId: number, type: 'video' | 'poster') => {
  if (el) {
    fileInputRefs.value[`${type}-${itemId}`] = el;
  }
};

const triggerFileInput = (itemId: number, type: 'video' | 'poster') => {
  fileInputRefs.value[`${type}-${itemId}`]?.click();
};

const resetFileInput = (itemId: number, type: 'video' | 'poster') => {
  const inputRef = fileInputRefs.value[`${type}-${itemId}`];
  if (inputRef) {
    inputRef.value = '';
  }
};

const togleAccardion = (id: number) => {
  openAccardion.value[id] = !openAccardion.value[id];

  if (openAccardion.value[id]) {
    // When opening, create a deep copy for editing.
    const originalItem = introSlideData.value.find((i) => i.id === id);
    if (originalItem) {
      editableItemData.value[id] = JSON.parse(JSON.stringify(originalItem));
      // Инициализируем режим ссылки для существующего слайда
      initializeLinkMode(id, originalItem.button_link);
    }
  } else {
    // When closing, remove the copy to discard changes.
    delete editableItemData.value[id];
    delete selectedLinks.value[id];
    delete linkSourceMode.value[id];
    delete customLinks.value[id];
  }
};

const togleAdvantageAccardion = (id: number) => {
  openAdvantageAccardion.value[id] = !openAdvantageAccardion.value[id];
};

const onFileChange = (
  event: Event,
  itemId: number,
  type: 'video' | 'poster'
) => {
  const input = event.target as HTMLInputElement;
  const file = input.files?.[0];

  if (!videoPreviews.value[itemId]) {
    videoPreviews.value[itemId] = {};
  }
  if (!filesToUpload.value[itemId]) {
    filesToUpload.value[itemId] = {};
  }

  if (!file) {
    videoPreviews.value[itemId][type] = undefined;
    filesToUpload.value[itemId][type] = undefined;
    return;
  }

  // Client-side validation
  if (type === 'video') {
    const max_video_size = 50 * 1024 * 1024; // 50 MB
    if (file.size > max_video_size) {
      Swal.fire(
        'Ошибка',
        'Размер видеофайла не должен превышать 50 МБ.',
        'error'
      );
      resetFileInput(itemId, type);
      return;
    }
  }
  if (type === 'poster') {
    const max_poster_size = 5 * 1024 * 1024; // 5 MB
    if (file.size > max_poster_size) {
      Swal.fire(
        'Ошибка',
        'Размер файла постера не должен превышать 5 МБ.',
        'error'
      );
      resetFileInput(itemId, type);
      return;
    }
  }

  filesToUpload.value[itemId][type] = file;
  videoPreviews.value[itemId][type] = URL.createObjectURL(file);
};

const clearFile = (itemId: number, type: 'video' | 'poster') => {
  const item = introSlideData.value.find((i) => i.id === itemId);
  if (!item) return;

  // Clear local preview and file selection
  if (videoPreviews.value[itemId]) {
    videoPreviews.value[itemId][type] = undefined;
  }
  if (filesToUpload.value[itemId]) {
    filesToUpload.value[itemId][type] = undefined;
  }

  // Clear existing path to hide preview
  if (type === 'video') {
    item.video_path = '';
  } else {
    item.poster_path = '';
  }

  // Mark for deletion on server
  if (!filesToDelete.value[itemId]) {
    filesToDelete.value[itemId] = {};
  }
  filesToDelete.value[itemId][type] = true;

  // Reset the file input
  const inputRef = fileInputRefs.value[`${type}-${itemId}`];
  if (inputRef) {
    inputRef.value = '';
  }
};

const introSlideStore = useIntroSlideStore();
const { introSlideData, isLoading } = storeToRefs(introSlideStore);

// API URLs для получения ссылок
const API_URL_LINKS = '/server/php/admin/api/linksData/linksData.php';
const API_URL_NAVIGATION = '/server/php/admin/api/navigation/navigation.php';

// Функция для загрузки всех ссылок из БД
const fetchAllLinks = async () => {
  try {
    // Загружаем ссылки из LinksData
    const linksResponse = await fetchWithCors(API_URL_LINKS);
    let linksData = [];

    if (linksResponse.success) {
      linksData = linksResponse.data;
    } else {
      console.warn(
        'Не удалось загрузить ссылки из LinksData:',
        linksResponse.error
      );
    }

    // Загружаем ссылки из Navigation
    const navResponse = await fetchWithCors(API_URL_NAVIGATION);
    let navData = [];

    if (navResponse.success) {
      // Преобразуем данные Navigation в формат LinkData
      navData = navResponse.data.map((item: any) => ({
        links_data_id: item.id,
        name: item.title,
        link: item.link,
        source_table: 'Navigation',
      }));
    } else {
      console.warn(
        'Не удалось загрузить ссылки из Navigation:',
        navResponse.error
      );
    }

    // Объединяем данные из обеих таблиц
    allLinksData.value = [...linksData, ...navData];
  } catch (err: any) {
    console.error('Ошибка загрузки ссылок:', err.message);
  }
};

// Функция для инициализации режима ссылки для слайда (с учетом tel:/mailto:)
const initializeLinkMode = (slideId: number, buttonLink: string) => {
  const normalizedButton = (() => {
    const t = detectTypeByValue(buttonLink || '');
    if (t === 'phone') return normalizePhoneHref(buttonLink || '');
    if (t === 'email') return normalizeEmailHref(buttonLink || '');
    return (buttonLink || '').trim();
  })();

  const existingLink = allLinksData.value.find((link) => {
    const linkNormalized = link.type === 'phone'
      ? normalizePhoneHref(link.link)
      : link.type === 'email'
      ? normalizeEmailHref(link.link)
      : (link.link || '').trim();
    return linkNormalized === normalizedButton;
  });

  if (existingLink) {
    linkSourceMode.value[slideId] = 'existing';
    selectedLinks.value[slideId] = existingLink;
  } else {
    linkSourceMode.value[slideId] = 'custom';
    customLinks.value[slideId] = buttonLink;
  }
};

// Computed для получения доступных ссылок (исключая уже выбранные)
const getAvailableLinks = (currentSlideId: number) => {
  const usedLinks = new Set(
    Object.entries(selectedLinks.value)
      .filter(([id, link]) => id !== currentSlideId.toString() && link)
      .map(([, link]) => link?.links_data_id)
  );

  return allLinksData.value.filter(
    (link) => !usedLinks.has(link.links_data_id)
  );
};

// Функция для обновления ссылки кнопки
const updateButtonLink = (slideId: number) => {
  const mode = linkSourceMode.value[slideId];
  let newLink = '';
  let linkType: 'link' | 'phone' | 'email' = 'link';

  if (mode === 'existing' && selectedLinks.value[slideId]) {
    const sel = selectedLinks.value[slideId]!;
    newLink = sel.link || '';
    linkType = (sel as any).type ?? 'link';
  } else if (mode === 'custom') {
    newLink = customLinks.value[slideId] || '';
    linkType = detectTypeByValue(newLink);
  }

  // Normalize by type
  if (linkType === 'phone') {
    newLink = normalizePhoneHref(newLink);
  } else if (linkType === 'email') {
    newLink = normalizeEmailHref(newLink);
  }

  // Обновляем ссылку в данных слайда
  if (editableItemData.value[slideId]) {
    editableItemData.value[slideId].button_link = newLink;
  }
  if (newSlide.value && slideId === 0) {
    newSlide.value.button_link = newLink;
  }
};

const addAdvantage = (item: IntroSlideData) => {
  if (!Array.isArray(item.advantages)) {
    item.advantages = [];
  }
  item.advantages.push('');
};

const removeAdvantage = (item: IntroSlideData, index: number) => {
  item.advantages.splice(index, 1);
};

// Диагностика ввода преимуществ: фиксируем события для поиска причин потери фокуса
const logAdvantageInput = (
  scope: 'new' | 'existing',
  slideId: number,
  index: number,
  value: string
) => {
  // Лог без тяжелых вычислений, чтобы не влиять на производительность ввода
  console.debug('[IntroSlide] advantage input', { scope, slideId, index, valueLength: value?.length ?? 0 });
};

const handleSave = async (item: IntroSlideData) => {
  try {
    if (
      !item.title ||
      !item.button_text ||
      !item.button_link ||
      item.title.trim() === '' ||
      item.button_text.trim() === '' ||
      item.button_link.trim() === ''
    ) {
      Swal.fire({
        icon: 'error',
        title: 'Ошибка валидации',
        text: 'Поля "Заголовок", "Текст кнопки" и "Ссылка кнопки" не могут быть пустыми.',
      });
      return;
    }
    // Filter out empty advantages before saving
    const cleanedAdvantages = item.advantages.filter(
      (adv) => adv && adv.trim() !== ''
    );
    // Перед сохранением ещё раз нормализуем ссылку по типу
    let normalizedLink = item.button_link || '';
    const t = detectTypeByValue(normalizedLink);
    if (t === 'phone') normalizedLink = normalizePhoneHref(normalizedLink);
    if (t === 'email') normalizedLink = normalizeEmailHref(normalizedLink);

    const itemToSave = { ...item, advantages: cleanedAdvantages, button_link: normalizedLink };

    const files = filesToUpload.value[item.id] || {};
    const toDelete = filesToDelete.value[item.id] || {};

    loadingAction.value = 'saving';
    await introSlideStore.updateIntroSlide(itemToSave, files, toDelete);

    // After successful save, clear temporary states and refresh data
    openAccardion.value[item.id] = false;
    delete editableItemData.value[item.id];
    delete videoPreviews.value[item.id];
    delete filesToUpload.value[item.id];
    delete filesToDelete.value[item.id];
    await introSlideStore.getIntroSlideData();

    Swal.fire({
      toast: true,
      position: 'top',
      icon: 'success',
      title: 'Слайд успешно сохранен!',
      showConfirmButton: false,
      timer: 2000,
    });
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка сохранения',
      text:
        error instanceof Error ? error.message : 'Произошла неизвестная ошибка',
    });
  }
};

const prepareNewSlide = () => {
  newSlide.value = {
    id: 0, // Temporary ID for a new slide
    title: 'Новый слайд',
    button_text: 'Кнопка',
    button_link: '#',
    advantages: ['Преимущество 1'],
    video_path: '',
    poster_path: '',
    video_path_mob: '',
    video_filename: '',
    position: 0,
    created_at: '',
    updated_at: '',
  };
  // Инициализируем режим ссылки для нового слайда
  linkSourceMode.value[0] = 'custom';
  customLinks.value[0] = '#';
  isAddingNewSlide.value = true;
};

const cancelNewSlide = () => {
  newSlide.value = null;
  isAddingNewSlide.value = false;
  delete filesToUpload.value[0];
  delete videoPreviews.value[0];
  delete selectedLinks.value[0];
  delete linkSourceMode.value[0];
  delete customLinks.value[0];
};

const handleSaveNewSlide = async () => {
  if (!newSlide.value) return;

  if (
    !newSlide.value.title ||
    !newSlide.value.button_text ||
    !newSlide.value.button_link ||
    newSlide.value.title.trim() === '' ||
    newSlide.value.button_text.trim() === '' ||
    newSlide.value.button_link.trim() === ''
  ) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка валидации',
      text: 'Поля "Заголовок", "Текст кнопки" и "Ссылка кнопки" не могут быть пустыми.',
    });
    return;
  }

  const cleanedAdvantages = newSlide.value.advantages.filter(
    (adv) => adv && adv.trim() !== ''
  );
  // Нормализуем ссылку для нового слайда
  let normalizedLink = newSlide.value.button_link || '';
  const t = detectTypeByValue(normalizedLink);
  if (t === 'phone') normalizedLink = normalizePhoneHref(normalizedLink);
  if (t === 'email') normalizedLink = normalizeEmailHref(normalizedLink);

  const slideToCreate = { ...newSlide.value, advantages: cleanedAdvantages, button_link: normalizedLink };
  const files = filesToUpload.value[0] || {};

  try {
    loadingAction.value = 'saving';
    await introSlideStore.createIntroSlide(slideToCreate, files);
    Swal.fire({
      toast: true,
      position: 'top',
      icon: 'success',
      title: 'Новый слайд успешно сохранен!',
      showConfirmButton: false,
      timer: 2000,
    });
    cancelNewSlide();
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка сохранения нового слайда',
      text:
        error instanceof Error ? error.message : 'Произошла неизвестная ошибка',
    });
  }
};

const handleDeleteSlide = async (slideId: number) => {
  Swal.fire({
    title: 'Вы уверены?',
    text: 'Вы не сможете восстановить этот слайд после удаления!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        loadingAction.value = 'deleting';
        await introSlideStore.deleteIntroSlide(slideId);
        Swal.fire('Удалено!', 'Слайд был успешно удален.', 'success');
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Ошибка удаления',
          text:
            error instanceof Error
              ? error.message
              : 'Произошла неизвестная ошибка',
        });
      }
    }
  });
};

const reorderSlides = async (reorderedSlides: IntroSlideData[]) => {
  try {
    const updateData = reorderedSlides.map((slide, index) => ({
      id: slide.id,
      position: index + 1,
    }));

    loadingAction.value = 'reordering';
    await introSlideStore.updateSlideOrder(updateData);

    Swal.fire({
      toast: true,
      position: 'top',
      icon: 'success',
      title: 'Порядок слайдов обновлен!',
      showConfirmButton: false,
      timer: 1500,
    });
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Ошибка обновления порядка',
      text:
        error instanceof Error ? error.message : 'Произошла неизвестная ошибка',
    });
    // Optional: revert local changes on error
    await introSlideStore.getIntroSlideData();
  }
};

onMounted(async () => {
  loadingAction.value = 'loading';
  await Promise.all([introSlideStore.getIntroSlideData(), fetchAllLinks()]);
});

watchEffect(() => {
  if (isLoading.value) {
    let title = '';
    let html = '';
    switch (loadingAction.value) {
      case 'saving':
        title = 'Сохранение данных';
        html =
          'Пожалуйста, подождите...<br>Это может занять некоторое время при загрузке видео.';
        break;
      case 'deleting':
        title = 'Удаление слайда';
        html = 'Пожалуйста, подождите...';
        break;
      case 'reordering':
        title = 'Обновление порядка';
        html = 'Пожалуйста, подождите...';
        break;
      default:
        title = 'Загрузка данных';
        html = 'Пожалуйста, подождите...';
    }
    Swal.fire({
      title: title,
      html: html,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  } else {
    Swal.close();
  }
});

// --- Transition Hooks for Accordion ---
const beforeEnter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = '0';
  htmlEl.style.opacity = '0';
  htmlEl.style.paddingTop = '0';
  htmlEl.style.paddingBottom = '0';
  htmlEl.style.marginTop = '0';
};
const enter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = `${htmlEl.scrollHeight}px`;
  htmlEl.style.opacity = '1';
  htmlEl.style.paddingTop = ''; // Reset to CSS value
  htmlEl.style.paddingBottom = ''; // Reset to CSS value
  htmlEl.style.marginTop = ''; // Reset to CSS value
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
  // This forces the browser to recognize the height before setting it to 0
  getComputedStyle(htmlEl).height;
  requestAnimationFrame(() => {
    htmlEl.style.height = '0';
    htmlEl.style.opacity = '0';
    htmlEl.style.paddingTop = '0';
    htmlEl.style.paddingBottom = '0';
    htmlEl.style.marginTop = '0';
  });
};
</script>
<template>
  <div>
    <h1 class="main-title">Главный слайдер на главной странице</h1>
    <div class="add-new-item-bar">
      <span>Добавить новый слайд</span>
      <MyBtn
        variant="secondary"
        @click="prepareNewSlide"
        :disabled="isAddingNewSlide"
        >Добавить</MyBtn
      >
    </div>

    <!-- Form for new slide -->
    <form
      v-if="newSlide"
      class="main-item new-slide-form"
      name="intro-slide-new"
    >
      <div class="main-item-content">
        <div class="main-item-inputs">
          <div class="main-item-input">
            <label class="main-item-input-label">Заголовок</label>
            <input
              class="main-item-input-field"
              type="text"
              v-model="newSlide.title"
            />
          </div>
          <div class="main-item-input">
            <label class="main-item-input-label">Текст кнопки</label>
            <input
              class="main-item-input-field"
              type="text"
              v-model="newSlide.button_text"
            />
          </div>
          <div class="main-item-input">
            <label class="main-item-input-label">Ссылка кнопки</label>
            <div class="link-selector-container">
              <div class="link-mode-toggle">
                <label :class="{ active: linkSourceMode[0] === 'custom' }">
                  <input
                    type="radio"
                    value="custom"
                    v-model="linkSourceMode[0]"
                    name="new-slide-link-mode"
                  />
                  Внешняя ссылка
                </label>
                <label :class="{ active: linkSourceMode[0] === 'existing' }">
                  <input
                    type="radio"
                    value="existing"
                    v-model="linkSourceMode[0]"
                    name="new-slide-link-mode"
                  />
                  Внутренняя страница
                </label>
              </div>

              <div
                v-if="linkSourceMode[0] === 'custom'"
                class="custom-link-input"
              >
                <input
                  class="main-item-input-field"
                  type="text"
                  v-model="customLinks[0]"
                  @input="updateButtonLink(0)"
                  placeholder="Введите ссылку"
                />
              </div>

              <div
                v-if="linkSourceMode[0] === 'existing'"
                class="existing-link-selector"
              >
                <LinkSelector
                  :model-value="selectedLinks[0]"
                  :links="getAvailableLinks(0)"
                  @update:model-value="(val: any) => { selectedLinks[0] = val; updateButtonLink(0); }"
                  label=""
                />
              </div>
            </div>
          </div>
        </div>

        <div class="main-item-advantages">
          <div class="main-item-advantages-header">
            <label class="main-item-input-label">Преимущества</label>
          </div>
          <div class="advantages-content">
            <div>
              <div
                v-if="newSlide.advantages.length > 0"
                class="advantages-list"
              >
                <div
                  v-for="(_, advIndex) in newSlide.advantages"
                  :key="`new-adv-${advIndex}`"
                  class="advantage-item"
                >
                  <input
                    class="input-advantage"
                    type="text"
                    v-model="newSlide.advantages[advIndex]"
                    @input="(e: any) => logAdvantageInput('new', 0, advIndex, (e?.target?.value ?? '') as string)"
                    placeholder="Введите преимущество"
                  />
                  <MyBtn
                    variant="primary"
                    @click.prevent="removeAdvantage(newSlide, advIndex)"
                    class="btn-remove"
                  >
                    Удалить</MyBtn
                  >
                </div>
              </div>
              <p v-else class="no-advantages">Сейчас нет преимуществ</p>
            </div>
            <div class="advantage-actions">
              <MyBtn
                variant="secondary"
                @click.prevent="addAdvantage(newSlide)"
                class="btn-add"
                >Добавить преимущество
              </MyBtn>
            </div>
          </div>
        </div>

        <div class="media-uploads">
          <!-- Video Upload for new slide -->
          <div class="upload-group">
            <label>Видео для слайда (не более ~50 МБ, формат mp4):</label>
            <input
              type="file"
              class="hidden-file-input"
              :ref="(el) => setFileInputRef(el, 0, 'video')"
              accept="video/mp4"
              @change="onFileChange($event, 0, 'video')"
            />
            <div class="uploader-preview video-uploader">
              <video
                v-if="videoPreviews[0]?.video"
                :src="videoPreviews[0]?.video"
                class="video-preview"
                controls
                muted
              ></video>
              <div v-else class="uploader-placeholder">
                <span>Нет видео</span>
              </div>
            </div>
            <div class="upload-actions">
              <MyBtn
                variant="secondary"
                type="button"
                @click="triggerFileInput(0, 'video')"
                >Выбрать видео</MyBtn
              >
              <MyBtn
                variant="primary"
                type="button"
                @click="clearFile(0, 'video')"
                >Удалить видео</MyBtn
              >
            </div>
          </div>

          <!-- Poster Upload for new slide -->
          <div class="upload-group">
            <label>
              Постер (не более ~5 МБ, формат jpg, обязательно загрузите, это
              важно для плавного появления видео):
            </label>
            <input
              type="file"
              class="hidden-file-input"
              :ref="(el) => setFileInputRef(el, 0, 'poster')"
              accept="image/*"
              @change="onFileChange($event, 0, 'poster')"
            />
            <div class="uploader-preview image-uploader">
              <img
                v-if="videoPreviews[0]?.poster"
                :src="videoPreviews[0]?.poster"
                alt="Постер"
                class="image-preview"
              />
              <div v-else class="uploader-placeholder">
                <span>Нет постера</span>
              </div>
            </div>
            <div class="upload-actions">
              <MyBtn
                variant="secondary"
                type="button"
                @click="triggerFileInput(0, 'poster')"
                >Выбрать постер</MyBtn
              >
              <MyBtn
                variant="primary"
                type="button"
                @click="clearFile(0, 'poster')"
                >Удалить постер</MyBtn
              >
            </div>
          </div>
        </div>

        <div class="form-actions">
          <MyBtn
            variant="secondary"
            @click.prevent="handleSaveNewSlide"
            :disabled="isLoading"
            class="save-all-btn"
          >
            {{ isLoading ? 'Сохранение...' : 'Сохранить новый слайд' }}
          </MyBtn>
          <MyBtn
            variant="primary"
            @click.prevent="cancelNewSlide"
            :disabled="isLoading"
            class="delete-slide-btn"
          >
            Отменить создание слайда</MyBtn
          >
        </div>
      </div>
    </form>

    <DraggableList
      v-if="introSlideData && introSlideData.length > 0"
      v-model="introSlideData"
      item-key="id"
      class="slides-list"
      tag="div"
      @reorder="reorderSlides"
    >
      <template #item="{ item, dragHandleProps, isDragOver }">
        <form
          class="main-item"
          :name="`intro-slide-${item.id}`"
          :class="{ 'drag-over': isDragOver }"
        >
          <label class="main-item-label" :for="`intro-slide-${item.id}`">
            <div class="main-item-label-content">
              <div class="drag-handle" v-bind="dragHandleProps">
                <img
                  style="display: block"
                  src="./../../assets/d-and-d.svg"
                  alt="Перетащить"
                  width="40"
                  height="40"
                />
              </div>
              {{ item.title }}
            </div>

            <MyBtn variant="primary" @click.prevent="togleAccardion(item.id)">{{
              openAccardion[item.id] ? 'Закрыть' : 'Редактировать'
            }}</MyBtn>
          </label>
          <Transition
            name="accordion"
            @before-enter="beforeEnter"
            @enter="enter"
            @after-enter="afterEnter"
            @before-leave="beforeLeave"
            @leave="leave"
          >
            <div
              v-if="openAccardion[item.id] && editableItemData[item.id]"
              class="main-item-content"
              data-drag-preview-hide="true"
            >
              <div class="main-item-inputs">
                <div class="main-item-input">
                  <label class="main-item-input-label">Заголовок</label>
                  <input
                    class="main-item-input-field"
                    type="text"
                    v-model="editableItemData[item.id].title"
                  />
                </div>
                <div class="main-item-input">
                  <label class="main-item-input-label">Текст кнопки</label>
                  <input
                    class="main-item-input-field"
                    type="text"
                    v-model="editableItemData[item.id].button_text"
                  />
                </div>
                <div class="main-item-input">
                  <label class="main-item-input-label">Ссылка кнопки</label>
                  <div class="link-selector-container">
                    <div class="link-mode-toggle">
                      <label
                        :class="{
                          active: linkSourceMode[item.id] === 'custom',
                        }"
                      >
                        <input
                          type="radio"
                          value="custom"
                          v-model="linkSourceMode[item.id]"
                          :name="`slide-${item.id}-link-mode`"
                        />
                        Внешняя ссылка
                      </label>
                      <label
                        :class="{
                          active: linkSourceMode[item.id] === 'existing',
                        }"
                      >
                        <input
                          type="radio"
                          value="existing"
                          v-model="linkSourceMode[item.id]"
                          :name="`slide-${item.id}-link-mode`"
                        />
                        Внутренняя страница
                      </label>
                    </div>

                    <div
                      v-if="linkSourceMode[item.id] === 'custom'"
                      class="custom-link-input"
                    >
                      <input
                        class="main-item-input-field"
                        type="text"
                        v-model="customLinks[item.id]"
                        @input="updateButtonLink(item.id)"
                        placeholder="Введите ссылку"
                      />
                    </div>

                    <div
                      v-if="linkSourceMode[item.id] === 'existing'"
                      class="existing-link-selector"
                    >
                      <LinkSelector
                        :model-value="selectedLinks[item.id]"
                        :links="getAvailableLinks(item.id)"
                        @update:model-value="(val: any) => { selectedLinks[item.id] = val; updateButtonLink(item.id); }"
                        label=""
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="main-item-advantages">
                <div class="main-item-advantages-header">
                  <label class="main-item-input-label">Преимущества</label>
                  <MyBtn
                    variant="primary"
                    @click.prevent="togleAdvantageAccardion(item.id)"
                    >{{
                      openAdvantageAccardion[item.id] ? 'Скрыть' : 'Показать'
                    }}
                    преимущества</MyBtn
                  >
                </div>
                <Transition
                  name="accordion"
                  @before-enter="beforeEnter"
                  @enter="enter"
                  @after-enter="afterEnter"
                  @before-leave="beforeLeave"
                  @leave="leave"
                >
                  <div
                    class="advantages-content"
                    v-if="openAdvantageAccardion[item.id]"
                  >
                    <div>
                      <div
                        v-if="editableItemData[item.id].advantages.length > 0"
                        class="advantages-list"
                      >
                        <div
                          v-for="(_, advIndex) in editableItemData[
                            item.id
                          ].advantages"
                          :key="`adv-${item.id}-${advIndex}`"
                          class="advantage-item"
                        >
                          <input
                            class="input-advantage"
                            type="text"
                            v-model="
                              editableItemData[item.id].advantages[advIndex]
                            "
                            @input="(e: any) => logAdvantageInput('existing', item.id, advIndex, (e?.target?.value ?? '') as string)"
                            placeholder="Введите преимущество"
                          />
                          <MyBtn
                            variant="primary"
                            @click.prevent="
                              removeAdvantage(
                                editableItemData[item.id],
                                advIndex
                              )
                            "
                            class="btn-remove"
                            >Удалить</MyBtn
                          >
                        </div>
                      </div>
                      <p v-else class="no-advantages">Сейчас нет преимуществ</p>
                    </div>

                    <div class="advantage-actions">
                      <MyBtn
                        variant="secondary"
                        @click.prevent="addAdvantage(editableItemData[item.id])"
                        class="btn-add"
                        >Добавить преимущество</MyBtn
                      >
                    </div>
                  </div>
                </Transition>
              </div>

              <div class="media-uploads">
                <!-- Video Upload -->
                <div class="upload-group">
                  <label>Видео для слайда (не более ~50 МБ, формат mp4):</label>
                  <input
                    type="file"
                    class="hidden-file-input"
                    :ref="(el) => setFileInputRef(el, item.id, 'video')"
                    accept="video/mp4"
                    @change="onFileChange($event, item.id, 'video')"
                  />
                  <div class="uploader-preview video-uploader">
                    <video
                      v-if="videoPreviews[item.id]?.video || item.video_path"
                      :src="videoPreviews[item.id]?.video || item.video_path"
                      class="video-preview"
                      controls
                      muted
                    ></video>
                    <div v-else class="uploader-placeholder">
                      <span>Нет видео</span>
                    </div>
                  </div>
                  <div class="upload-actions">
                    <MyBtn
                      variant="secondary"
                      type="button"
                      @click="triggerFileInput(item.id, 'video')"
                      >Выбрать видео
                    </MyBtn>
                    <MyBtn
                      variant="primary"
                      type="button"
                      @click="clearFile(item.id, 'video')"
                      >Удалить видео</MyBtn
                    >
                  </div>
                </div>

                <!-- Poster Upload -->
                <div class="upload-group">
                  <label> Постер (не более ~5 МБ, формат jpg): </label>
                  <input
                    type="file"
                    class="hidden-file-input"
                    :ref="(el) => setFileInputRef(el, item.id, 'poster')"
                    accept="image/*"
                    @change="onFileChange($event, item.id, 'poster')"
                  />
                  <div class="uploader-preview image-uploader">
                    <img
                      v-if="videoPreviews[item.id]?.poster || item.poster_path"
                      :src="videoPreviews[item.id]?.poster || item.poster_path"
                      alt="Постер"
                      class="image-preview"
                    />
                    <div v-else class="uploader-placeholder">
                      <span>Нет постера</span>
                    </div>
                  </div>
                  <div class="upload-actions">
                    <MyBtn
                      variant="secondary"
                      type="button"
                      @click="triggerFileInput(item.id, 'poster')"
                      >Выбрать постер
                    </MyBtn>
                    <MyBtn
                      variant="primary"
                      type="button"
                      @click="clearFile(item.id, 'poster')"
                      >Удалить постер</MyBtn
                    >
                  </div>
                </div>
              </div>

              <div class="form-actions">
                <MyBtn
                  variant="secondary"
                  @click.prevent="handleSave(editableItemData[item.id])"
                  :disabled="isLoading"
                  class="save-all-btn"
                >
                  {{ isLoading ? 'Сохранение...' : 'Сохранить слайд' }}
                </MyBtn>
                <MyBtn
                  variant="primary"
                  @click.prevent="handleDeleteSlide(item.id)"
                  :disabled="isLoading"
                  class="delete-slide-btn"
                  >Удалить слайд</MyBtn
                >
              </div>
              <!-- <p>Это position: {{ item.position }}</p> -->
              <!-- <p>Это created_at: {{ item.created_at }}</p> -->
            </div>
          </Transition>
        </form>
      </template>
    </DraggableList>
  </div>
</template>

<style scoped>
@import url('./introSiide.module.css');

</style>
