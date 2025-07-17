import { ref } from 'vue';
import type { Ref } from 'vue';
import Swal from 'sweetalert2';
import type { UploadButtonInstance, Slide } from '../interfaces/types';

export function useVideo(
  items: Ref<Slide[]>,
  currentSlideIndex: Ref<number>,
  uploadButtonRef: Ref<UploadButtonInstance[]>
) {
  const videoPreview = ref<string | null>(null);
  const uploadProgress = ref<number>(0);
  const formData = new FormData();

  function handleUploadSuccess(data: {
    id: number;
    filename: string;
    path: string;
  }) {
    const slide = items.value.find((item) => item.id === data.id);
    if (slide) {
      slide.video_path = data.path;
      if (items.value[currentSlideIndex.value]?.id === data.id) {
        videoPreview.value = data.path;
      }
    }
  }

  function handleStatusUpdate(status: string) {
    if (status.includes('успешно') || status.includes('завершена')) {
      Swal.fire({
        icon: 'success',
        title: 'Успех',
        text: status,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
      });
    } else if (status.includes('Ошибка')) {
      Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: status,
      });
    }
  }

  function handleProgressUpdate(progress: number) {
    uploadProgress.value = progress;
  }

  function handleVideoPreview(preview: string) {
    videoPreview.value = preview;
  }

  function handleVideoDeleted(videoId: number) {
    const slide = items.value.find((item) => item.id === videoId);
    if (slide) {
      slide.video_path = '';
    }
    if (items.value[currentSlideIndex.value]?.id === videoId) {
      videoPreview.value = null;
    }
    uploadProgress.value = 0;
    const currentUploadButton = uploadButtonRef.value[currentSlideIndex.value];
    if (currentUploadButton) {
      currentUploadButton.clearInput();
    }
  }

  return {
    videoPreview,
    uploadProgress,
    formData,
    handleUploadSuccess,
    handleStatusUpdate,
    handleProgressUpdate,
    handleVideoPreview,
    handleVideoDeleted,
  };
}
