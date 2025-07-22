<script setup lang="ts">
import { ref, watch, toRefs } from 'vue';
import type { Tab, DescriptionItem } from './interfaces/Products';
import Loader from '../../UI/Loader.vue';

defineOptions({
  name: 'Tabs',
});

const props = defineProps<{
  tabs: Tab[];
  isIconUploading: (tabIndex: number, itemIndex: number) => boolean;
  serverBaseUrl: string;
}>();

const { tabs } = toRefs(props);
const localTabs = ref<Tab[]>([]);
const editingTabIndex = ref<number | null>(null);
const originalTab = ref<Tab | null>(null);

const emit = defineEmits<{
  'update:tabs': [tabs: Tab[]];
  triggerIconUpload: [tabIndex: number, itemIndex: number];
  deleteIcon: [tabIndex: number, itemIndex: number];
}>();

watch(
  tabs,
  (newTabs) => {
    if (editingTabIndex.value === null) {
      localTabs.value = JSON.parse(JSON.stringify(newTabs || []));
    }
  },
  { deep: true }
);

watch(localTabs, (newTabs) => {
  emit('update:tabs', newTabs);
});

function addAndEditTab() {
  const newTab: Tab = {
    title: 'Новая вкладка',
    description: [],
  };
  localTabs.value.push(newTab);
  editTab(localTabs.value.length - 1);
}

function editTab(index: number) {
  originalTab.value = JSON.parse(JSON.stringify(localTabs.value[index]));
  editingTabIndex.value = index;
}

function saveTab() {
  editingTabIndex.value = null;
  originalTab.value = null;
}

function cancelEdit(index: number) {
  if (originalTab.value) {
    localTabs.value[index] = originalTab.value;
  } else {
    localTabs.value.splice(index, 1);
  }
  editingTabIndex.value = null;
  originalTab.value = null;
}

function removeTab(index: number) {
  localTabs.value.splice(index, 1);
}

function addItem(tab: Tab) {
  tab.description.push({
    title: 'Новый заголовок',
    description: 'Новое описание',
    'path-icon': '',
  });
}

function removeItem(tab: Tab, itemIndex: number) {
  tab.description.splice(itemIndex, 1);
}

function getFullIconPath(path: string | undefined): string {
  if (!path) return '';
  if (path.startsWith('blob:') || path.startsWith('http')) {
    return path;
  }
  return `${props.serverBaseUrl}${path}`;
}
</script>

<template>
  <div class="tabs-editor">
    <h3>Редактор Вкладок</h3>
    <div v-for="(tab, index) in localTabs" :key="index" class="tab-item">
      <!-- Editing State -->
      <div v-if="editingTabIndex === index">
        <div class="form-group">
          <label>Заголовок вкладки:</label>
          <input type="text" v-model="tab.title" />
        </div>

        <div class="description-section-editor">
          <h5>Содержимое вкладки</h5>
          <div
            v-for="(item, itemIndex) in tab.description"
            :key="itemIndex"
            class="description-item-editor"
          >
            <div class="form-group">
              <label>Заголовок элемента:</label>
              <input type="text" v-model="item.title" />
            </div>
            <div class="form-group">
              <label>Иконка:</label>
              <div class="icon-uploader">
                <!-- Case 1: Icon exists (show image and potential replacement loader) -->
                <div
                  v-if="item['path-icon']"
                  class="icon-container"
                  @click="
                    !isIconUploading(index, itemIndex) &&
                      emit('triggerIconUpload', index, itemIndex)
                  "
                >
                  <div
                    v-if="isIconUploading(index, itemIndex)"
                    class="loader-overlay"
                  >
                    <Loader size="small" />
                  </div>
                  <img
                    :src="getFullIconPath(item['path-icon'])"
                    alt="icon"
                    :class="{ uploading: isIconUploading(index, itemIndex) }"
                  />
                  <button
                    v-if="!isIconUploading(index, itemIndex)"
                    class="btn-delete-img"
                    @click.stop="emit('deleteIcon', index, itemIndex)"
                  ></button>
                </div>

                <!-- Case 2: No icon exists -->
                <template v-else>
                  <!-- Show loader if uploading for this item -->
                  <div
                    v-if="isIconUploading(index, itemIndex)"
                    class="icon-placeholder"
                  >
                    <Loader size="small" />
                  </div>
                  <!-- Show '+' placeholder if not uploading -->
                  <div
                    v-else
                    class="icon-placeholder"
                    @click="emit('triggerIconUpload', index, itemIndex)"
                  >
                    <span class="plus-icon">+</span>
                  </div>
                </template>
              </div>
            </div>
            <div class="form-group">
              <label>Описание элемента:</label>
              <textarea v-model="item.description"></textarea>
            </div>
            <button @click="removeItem(tab, itemIndex)" class="btn-delete">
              Удалить элемент
            </button>
          </div>
          <button
            @click="addItem(tab)"
            class="btn-add"
            style="margin-top: 10px"
          >
            Добавить элемент
          </button>
        </div>

        <div class="edit-controls">
          <button @click="saveTab" class="btn-save">Сохранить</button>
          <button @click="cancelEdit(index)" class="btn-cancel">Отмена</button>
        </div>
      </div>

      <!-- Display State -->
      <div v-else class="tab-display">
        <div class="tab-main-content">
          <strong>{{ tab.title }}</strong>
        </div>
        <div class="tab-controls">
          <button @click="editTab(index)" class="btn-edit">
            Редактировать
          </button>
          <button @click="removeTab(index)" class="btn-delete">Удалить</button>
        </div>
      </div>
    </div>
    <button @click="addAndEditTab" class="btn-add">Добавить вкладку</button>
  </div>
</template>

<style scoped>
.tabs-editor {
  border-top: 1px solid #555;
  padding-top: 15px;
  margin-top: 20px;
}
.tab-item,
.tab-display {
  border: 1px solid #4a4a4a;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 5px;
  background-color: #3e3e3e;
}
.tab-display {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.description-section-editor {
  border: 1px solid #555;
  padding: 15px;
  margin-top: 15px;
  border-radius: 5px;
  background-color: #4a4a4a;
}
.description-item-editor {
  border: 1px dashed #666;
  padding: 10px;
  margin-top: 10px;
  border-radius: 5px;
}
.form-group {
  margin-bottom: 10px;
}
.edit-controls,
.tab-controls {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}
.btn-add,
.btn-delete,
.btn-edit,
.btn-save,
.btn-cancel {
  padding: 8px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  color: white;
  transition: background-color 0.2s;
}
.btn-add {
  background-color: #007bff;
}
.btn-delete {
  background-color: #dc3545;
}
.btn-edit {
  background-color: #ffc107;
  color: black;
}
.btn-save {
  background-color: #28a745;
}
.btn-cancel {
  background-color: #6c757d;
}
/* Icon uploader styles */
.icon-uploader {
  display: flex;
}
.icon-container,
.icon-placeholder {
  position: relative;
  width: 50px;
  height: 50px;
  border: 1px dashed #666;
  border-radius: 4px;
  cursor: pointer;
}
.icon-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 4px;
}
.icon-container img.uploading {
  opacity: 0.5;
}
.loader-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 4px;
}
.icon-placeholder {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #555;
}
.icon-placeholder:hover {
  background-color: #666;
}
.plus-icon {
  font-size: 24px;
  color: #888;
}
.btn-delete-img {
  position: absolute;
  top: -8px;
  right: -8px;
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  width: 20px;
  height: 20px;
  padding: 0;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
}
.btn-delete-img::before,
.btn-delete-img::after {
  content: '';
  position: absolute;
  width: 10px;
  height: 2px;
  background-color: white;
}
.btn-delete-img::before {
  transform: rotate(45deg);
}
.btn-delete-img::after {
  transform: rotate(-45deg);
}
</style>
