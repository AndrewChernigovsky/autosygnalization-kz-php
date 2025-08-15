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

  const createIntroSlide = async (
    slideData: IntroSlideData,
    files: { video?: File; poster?: File }
  ) => {
    isLoading.value = true;
    try {
      const formData = new FormData();
      formData.append('action', 'create');
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

      const response = await fetchWithCors(API_BASE_URL, {
        method: 'POST',
        body: formData,
      });

      if (!response.success) {
        throw new Error(response.error || 'Не удалось создать слайд');
      }

      await getIntroSlideData();
    } catch (err) {
      throw new Error(
        err instanceof Error
          ? err.message
          : 'Неизвестная ошибка при создании слайда'
      );
    } finally {
      isLoading.value = false;
    }
  };

  const deleteIntroSlide = async (slideId: number) => {
    isLoading.value = true;
    try {
      const formData = new FormData();
      formData.append('action', 'delete');
      formData.append('id', String(slideId));

      const response = await fetchWithCors(API_BASE_URL, {
        method: 'POST',
        body: formData,
      });

      if (!response.success) {
        throw new Error(response.error || 'Не удалось удалить слайд');
      }

      await getIntroSlideData();
    } catch (err) {
      throw new Error(
        err instanceof Error
          ? err.message
          : 'Неизвестная ошибка при удалении слайда'
      );
    } finally {
      isLoading.value = false;
    }
  };

  const updateSlideOrder = async (
    orderData: { id: number; position: number }[]
  ) => {
    // Optimistic update
    const originalOrder = JSON.parse(JSON.stringify(introSlideData.value));
    const newOrderedSlides = orderData
      .map((data) => {
        const slide = introSlideData.value.find((s) => s.id === data.id);
        return { ...slide, position: data.position };
      })
      .sort((a, b) => a.position - b.position);
    introSlideData.value = newOrderedSlides as IntroSlideData[];

    try {
      const formData = new FormData();
      formData.append('action', 'update_order');
      formData.append('order', JSON.stringify(orderData));

      const response = await fetchWithCors(API_BASE_URL, {
        method: 'POST',
        body: formData,
      });

      if (!response.success) {
        throw new Error(
          response.error || 'Не удалось обновить порядок слайдов'
        );
      }
    } catch (err) {
      // Revert on error
      introSlideData.value = originalOrder;
      throw new Error(
        err instanceof Error
          ? err.message
          : 'Неизвестная ошибка при обновлении порядка'
      );
    }
  };

  return {
    introSlideData,
    isLoading,
    getIntroSlideData,
    updateIntroSlide,
    createIntroSlide,
    deleteIntroSlide,
    updateSlideOrder,
  };
});

export default useIntroSlideStore;
