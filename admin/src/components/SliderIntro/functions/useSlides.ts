import { ref, reactive } from 'vue';
import type { Slide } from '../interfaces/types';
import Swal from 'sweetalert2';
import { useIframeStore } from '../../../stores/iframeStore';

export function useSlides() {
  const iframeStore = useIframeStore();
  const items = ref<Slide[]>([]);

  async function fetchSlides() {
    try {
      const response = await fetch('/server/php/api/get-slides.php');
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      items.value = data.map((video: any) => ({
        ...video,
        advantages: video.advantages || [],
        button_text: video.button_text || 'Подробнее',
        link: video.button_link || '#',
      }));
    } catch (error) {
      console.error('Ошибка загрузки данных видео:', error);
      Swal.fire('Ошибка!', 'Произошла ошибка при загрузке данных.', 'error');
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
          position: items.value.length + 1,
          video_path: '',
          button_text: 'Подробнее',
        });
        Swal.fire('Добавлено!', 'Новый слайд был успешно добавлен.', 'success');
        iframeStore.triggerIframeRefresh();
      } else {
        Swal.fire('Ошибка!', 'Не удалось добавить новый слайд.', 'error');
      }
    } catch (error) {
      console.error('Ошибка добавления слайда:', error);
      Swal.fire('Ошибка!', 'Произошла ошибка при добавлении слайда.', 'error');
    }
  }

  async function removeSlide(id: number | undefined, index: number) {
    if (!id) return;

    Swal.fire({
      title: 'Вы уверены?',
      text: 'Вы не сможете отменить это действие!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Да, удалить!',
      cancelButtonText: 'Отмена',
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch(
            '/server/php/admin/api/delete-slide.php',
            {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ id }),
            }
          );
          if (response.ok) {
            items.value.splice(index, 1);
            Swal.fire('Удалено!', 'Слайд был успешно удален.', 'success');
            iframeStore.triggerIframeRefresh();
          } else {
            Swal.fire('Ошибка!', 'Не удалось удалить слайд.', 'error');
          }
        } catch (error) {
          console.error('Ошибка удаления слайда:', error);
          Swal.fire(
            'Ошибка!',
            'Произошла ошибка при удалении слайда.',
            'error'
          );
        }
      }
    });
  }

  async function updateSlide(slide: Slide) {
    const slideData = {
      id: slide.id,
      title: slide.title,
      advantages: JSON.stringify(slide.advantages),
      button_text: slide.button_text,
      button_link: slide.link,
    };

    try {
      const response = await fetch('/server/php/admin/api/update-slide.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(slideData),
      });
      if (response.ok) {
        Swal.fire('Сохранено!', 'Данные слайда успешно обновлены.', 'success');
        iframeStore.triggerIframeRefresh();
      } else {
        Swal.fire('Ошибка!', 'Не удалось сохранить данные слайда.', 'error');
      }
    } catch (error) {
      console.error('Ошибка отправки данных:', error);
      Swal.fire('Ошибка!', 'Произошла ошибка при отправке данных.', 'error');
    }
  }

  function addAdvantage(slide: Slide) {
    slide.advantages.push('');
  }

  function removeAdvantage(slide: Slide, index: number) {
    slide.advantages.splice(index, 1);
  }

  return {
    items,
    fetchSlides,
    addSlide,
    removeSlide,
    updateSlide,
    addAdvantage,
    removeAdvantage,
  };
}
