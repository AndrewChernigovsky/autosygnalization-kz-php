<template>
  <div class="tabs-editor">
    <h3>Редактор Вкладок</h3>
    <div v-for="(tabItems, category) in localTabs" :key="category">
      <h4>{{ category }}</h4>
      <div v-for="(tab, index) in tabItems" :key="index" class="tab-item">
        <div class="form-group" v-if="isEditing">
          <label>Заголовок:</label>
          <input type="text" v-model="tab.title" />
        </div>
        <div v-else>
          <strong>{{ tab.title }}:</strong>
        </div>

        <div class="form-group" v-if="isEditing">
          <label>Описание:</label>
          <textarea v-model="tab.description"></textarea>
        </div>
        <div v-else>
          <p>{{ tab.description }}</p>
        </div>
        <button v-if="isEditing" @click="removeTab(category, index)">
          Удалить
        </button>
      </div>
      <button v-if="isEditing" @click="addTab(category)">
        Добавить {{ category }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, toRefs } from 'vue';
import type { Tab } from './interfaces/Products';

defineOptions({ name: 'TabsEditor' });

const props = defineProps<{
  tabs: Record<string, Tab[]>;
  isEditing: boolean;
}>();

const { tabs } = toRefs(props);
const localTabs = ref<Record<string, Tab[]>>({});

const emit = defineEmits(['update:tabs']);

watch(
  localTabs,
  (newTabs) => {
    emit('update:tabs', newTabs);
  },
  { deep: true }
);

watch(tabs, (newTabs) => {
  localTabs.value = JSON.parse(JSON.stringify(newTabs || []));
});

function addTab(category: string) {
  if (!localTabs.value[category]) {
    localTabs.value[category] = [];
  }
  localTabs.value[category].push({
    title: 'Новая характеристика',
    description: 'Описание',
  });
}

function removeTab(category: string, index: number) {
  if (localTabs.value[category]) {
    localTabs.value[category].splice(index, 1);
  }
}
</script>

<script lang="ts">
export default {
  name: 'Tabs',
};
</script>

<style scoped>
.tabs-editor {
  border-top: 1px solid #555;
  padding-top: 15px;
  margin-top: 20px;
}
.tab-editor-item {
  background-color: #4a4a4a;
  border: 1px solid #666;
  padding: 15px;
  border-radius: 5px;
  margin-bottom: 10px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.form-control {
  width: 100%;
  padding: 10px;
  border: 1px solid #666;
  background-color: #555;
  color: #fff;
  border-radius: 4px;
}
textarea.form-control {
  min-height: 150px;
}
.btn-delete-tab,
.btn-add-tab {
  padding: 8px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  color: white;
  transition: background-color 0.2s;
  align-self: flex-start;
}
.btn-delete-tab {
  background-color: #dc3545;
}
.btn-delete-tab:hover {
  background-color: #c82333;
}
.btn-add-tab {
  background-color: #007bff;
}
.btn-add-tab:hover {
  background-color: #0069d9;
}
</style>
