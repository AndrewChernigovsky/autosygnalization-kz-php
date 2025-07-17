import { ref, nextTick } from 'vue';
import type { Ref } from 'vue';
import type { Slide } from '../interfaces/types';
import Swal from 'sweetalert2';
import { useIframeStore } from '../../../stores/iframeStore';

export function useDnD(items: Ref<Slide[]>, swiperInstance: Ref<any>) {
  const iframeStore = useIframeStore();

  function onDrop(dropResult: any) {
    const { removedIndex, addedIndex } = dropResult;
    if (removedIndex === null && addedIndex === null) return;

    const newItems = [...items.value];
    const [removed] = newItems.splice(removedIndex, 1);
    newItems.splice(addedIndex, 0, removed);
    items.value = newItems;

    handleDragEnd();

    nextTick(() => {
      if (swiperInstance.value) {
        swiperInstance.value.update();
      }
    });
  }

  function handleDragEnd() {
    items.value.forEach((item, index) => {
      item.position = index + 1;
    });
  }

  async function saveOrder() {
    const order = items.value.map((item) => ({
      id: item.id,
      position: item.position,
    }));

    try {
      const response = await fetch(
        '/server/php/admin/api/update-slides-order.php',
        {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ order }),
        }
      );
      if (response.ok) {
        Swal.fire(
          'Сохранено!',
          'Новый порядок слайдов успешно сохранен.',
          'success'
        );
        iframeStore.triggerIframeRefresh();
      } else {
        Swal.fire('Ошибка!', 'Не удалось сохранить порядок слайдов.', 'error');
      }
    } catch (error) {
      console.error('Ошибка сохранения порядка:', error);
      Swal.fire('Ошибка!', 'Произошла ошибка при сохранении порядка.', 'error');
    }
  }

  return { onDrop, saveOrder };
}
