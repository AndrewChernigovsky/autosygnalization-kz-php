import { ref } from 'vue';
import type { Ref } from 'vue';
import Swal from 'sweetalert2';
import type { UploadButtonInstance } from '../interfaces/types';

export function useVideo(
  items: Ref<any[]>,
  currentSlideIndex: Ref<number>,
  uploadButtonRef: Ref<UploadButtonInstance[]>
) {
  const videoPreview = ref<string | null>(null);
  const uploadProgress = ref<number>(0);
  const currentVideoId = ref<number | null>(null);
  const formData = new FormData();

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

  function handleVideoDeleted() {
    videoPreview.value = null;
    currentVideoId.value = null;
    uploadProgress.value = 0;
    if (items.value[currentSlideIndex.value]) {
      items.value[currentSlideIndex.value].video_path = '';
    }

    const currentUploadButton = uploadButtonRef.value[currentSlideIndex.value];
    if (currentUploadButton) {
      currentUploadButton.clearInput();
    }
  }

  return {
    videoPreview,
    uploadProgress,
    currentVideoId,
    formData,
    handleUploadSuccess,
    handleStatusUpdate,
    handleProgressUpdate,
    handleVideoPreview,
    handleVideoDeleted,
  };
}
