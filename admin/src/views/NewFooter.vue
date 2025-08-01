<script setup lang="ts">
import { ref, onMounted } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';
import LinkSelector from '../components/UI/LinkSelector.vue';

interface LinkData {
  links_data_id: number;
  name: string;
  link: string;
  source_table: string;
}

const allLinksData = ref<LinkData[]>([]);
const selectedLink = ref<string>('');

const API_URL = '/server/php/admin/api/linksData/linksData.php';

const getLinksData = async () => {
  try {
    const response = await fetchWithCors(API_URL);
    if (!response.success)
      throw new Error(response.error || 'Failed to fetch links data');
    allLinksData.value = response.data;
  } catch (err: any) {
    console.error('Error fetching links data:', err);
  }
};

onMounted(() => {
  getLinksData();
});
</script>

<template>
  <div class="all-links-data">
    <LinkSelector
      v-model="selectedLink"
      :links="allLinksData"
      label="Выберите ссылку"
      id="footer-link-selector"
    />
    <div v-if="selectedLink" class="selected-info">
      <p>Выбранная ссылка:</p>
      <code>{{ selectedLink }}</code>
    </div>
  </div>
</template>

<style scoped>
.all-links-data {
  padding: 20px;
}
.selected-info {
  margin-top: 20px;
  padding: 15px;
  background-color: #f0f4f8;
  border-radius: 5px;
}
code {
  background-color: #e2e8f0;
  padding: 3px 6px;
  border-radius: 4px;
}
</style>
