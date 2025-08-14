import { defineStore } from 'pinia';
import type { IntroSlideData } from '../components/IntroSlide/interfaces/introSlideData';
import { ref } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';

const useIntroSlideStore = defineStore('introSlideStore', () => {
  const introSlideData = ref<IntroSlideData[]>([]);
  const isLoading = ref(false);
  const API_BASE_URL = '/server/php/admin/api/intro-slide/intro-slide.php';

  const getIntroSlideData = async () => {
    isLoading.value = true;
    try {
      const response = await fetchWithCors(API_BASE_URL);
      if (response.success) {
        console.log(response.data);
        introSlideData.value = response.data;
      } else {
        throw new Error(response.error || 'Не удалось загрузить данные');
      }
    } catch (err) {
      throw new Error(
        err instanceof Error ? err.message : 'Неизвестная ошибка'
      );
    } finally {
      isLoading.value = false;
    }
  };

  const updateIntroSlide = async (
    slideData: IntroSlideData,
    files: { video?: File; poster?: File },
    toDelete: { video?: boolean; poster?: boolean }
  ) => {
    isLoading.value = true;
    try {
      const formData = new FormData();
      formData.append('id', String(slideData.id));
      formData.append('title', slideData.title);
      formData.append('button_text', slideData.button_text);
      formData.append('button_link', slideData.button_link);
      formData.append('advantages', JSON.stringify(slideData.advantages));

      if (files.video) {
        formData.append('video', files.video);
      }
      if (files.poster) {
        formData.append('poster', files.poster);
      }

      if (toDelete.video) {
        formData.append('remove_video', '1');
      }
      if (toDelete.poster) {
        formData.append('remove_poster', '1');
      }

      const response = await fetchWithCors(API_BASE_URL, {
        method: 'POST',
        body: formData,
      });

      if (!response.success) {
        throw new Error(response.error || 'Не удалось обновить данные');
      }
    } catch (err) {
      throw new Error(
        err instanceof Error ? err.message : 'Неизвестная ошибка при обновлении'
      );
    } finally {
      isLoading.value = false;
    }
  };

  return {
    introSlideData,
    isLoading,
    getIntroSlideData,
    updateIntroSlide,
  };
});

export default useIntroSlideStore;
