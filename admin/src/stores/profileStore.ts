import { defineStore } from 'pinia';
import { ref } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';

interface IProfile {
  id: number;
  username: string;
  email: string;
}

const profileStore = defineStore('profileStore', () => {
  const profile = ref<IProfile | null>(null);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  const getProfile = async () => {
    try {
      isLoading.value = true;
      error.value = null;

      const response = await fetchWithCors(
        '/server/php/admin/api/profile/profile.php'
      );

      if (response.success && response.data) {
        profile.value = response.data;
      } else {
        throw new Error(response.error || 'Failed to load profile');
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Unknown error';
      console.error('Ошибка загрузки профиля:', err);
    } finally {
      isLoading.value = false;
    }
  };

  const resetProfile = () => {
    profile.value = null;
    error.value = null;
  };

  return {
    profile,
    isLoading,
    error,
    getProfile,
    resetProfile,
  };
});

export default profileStore;
