import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useIframeStore = defineStore('iframe', () => {
  const refreshKey = ref(0);

  function triggerIframeRefresh() {
    refreshKey.value++;
  }

  return {
    refreshKey,
    triggerIframeRefresh,
  };
});
