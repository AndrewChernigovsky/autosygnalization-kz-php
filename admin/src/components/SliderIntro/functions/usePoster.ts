import { ref } from 'vue';
import type { Ref } from 'vue';
import Swal from 'sweetalert2';
import type { Slide } from '../interfaces/types';
import { useIframeStore } from '../../../stores/iframeStore';

export function usePoster(items: Ref<Slide[]>) {
  const iframeStore = useIframeStore();
  const posterPreview = ref<string | null>(null);
  const isDeleting = ref<Record<number, boolean>>({});

  async function handlePosterUpload(event: Event, slideId: number | undefined) {
    if (!slideId) {
      Swal.fire('Ошибка', 'Необходимо сначала сохранить слайд.', 'error');
      return;
    }

    const input = event.target as HTMLInputElement;
    if (!input.files?.length) return;
    const file = input.files[0];

    const formData = new FormData();
    formData.append('poster', file);
    formData.append('slide_id', String(slideId));

    try {
      const response = await fetch('/server/php/admin/api/upload-poster.php', {
        method: 'POST',
        body: formData,
      });

      const result = await response.json();
      if (!response.ok) {
        throw new Error(result.error || 'Ошибка загрузки постера');
      }

      const slide = items.value.find((item) => item.id === result.id);
      if (slide) {
        slide.poster_path = result.path;
      }

      Swal.fire('Успех!', 'Постер успешно загружен.', 'success');
      iframeStore.triggerIframeRefresh();
    } catch (error: any) {
      Swal.fire('Ошибка!', error.message, 'error');
    }
  }

  async function handlePosterDeleted(slideId: number | undefined) {
    if (!slideId) return;

    isDeleting.value[slideId] = true;
    try {
      const response = await fetch('/server/php/admin/api/delete-poster.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: slideId }),
      });

      const result = await response.json();
      if (!response.ok) {
        throw new Error(result.error || 'Ошибка удаления постера');
      }

      const slide = items.value.find((item) => item.id === slideId);
      if (slide) {
        slide.poster_path = '';
      }
      Swal.fire('Успех!', 'Постер удален.', 'success');
      iframeStore.triggerIframeRefresh();
    } catch (error: any) {
      Swal.fire('Ошибка!', error.message, 'error');
    } finally {
      isDeleting.value[slideId] = false;
    }
  }

  return {
    posterPreview,
    handlePosterUpload,
    handlePosterDeleted,
    isDeleting,
  };
}
