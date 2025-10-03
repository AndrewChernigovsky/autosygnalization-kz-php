<script setup lang="ts">
import { ref, watch } from 'vue';
import MyBtn from '../UI/MyBtn.vue';
import MyTransition from '../UI/MyTransition.vue';
import MyQuill from '../UI/MyQuill.vue';
import type { ProductI } from './interfaces/Products';

defineOptions({
  name: 'Tabs',
});

defineEmits(['upload-icon', 'delete-icon', 'update-tab-title', 'update-item']);

const props = defineProps<{
  product: ProductI;
}>();

const openTabs = ref<Record<number, boolean>>({});
const openAccardion = ref<Record<number, boolean>>({});
const openItems = ref<Record<string, boolean>>({});

const toggleItem = (tabIndex: number, itemIndex: number) => {
  const key = `${tabIndex}_${itemIndex}`;
  const current = openItems.value[key];
  openItems.value[key] = !(current ?? false);
};

// Инициализируем состояние openItems: по умолчанию все закрыты, открыт только первый элемент каждой вкладки
watch(() => props.product.tabs, (tabs) => {
  if (!Array.isArray(tabs)) return;

  // Если ещё не инициализировали openItems — инициализируем (первичный рендер)
  if (Object.keys(openItems.value).length === 0) {
    const newState: Record<string, boolean> = {};
    tabs.forEach((tab, tIdx) => {
      if (tab && Array.isArray(tab.content)) {
        tab.content.forEach((_, iIdx) => {
          const key = `${tIdx}_${iIdx}`;
          newState[key] = iIdx === 0; // только первый элемент открыт
        });
      }
    });
    openItems.value = newState;
    return;
  }

  // При последующих изменениях добавляем только новые ключи, не сбрасывая текущие состояния
  tabs.forEach((tab, tIdx) => {
    if (!tab || !Array.isArray(tab.content)) return;
    tab.content.forEach((_, iIdx) => {
      const key = `${tIdx}_${iIdx}`;
      if (!(key in openItems.value)) {
        openItems.value[key] = iIdx === 0;
      }
    });
  });
}, { immediate: true, deep: true });

const toggleTab = (index: number) => {
  openTabs.value[index] = !openTabs.value[index];
};
const toggleAccardion = (index: number) => {
  openAccardion.value[index] = !openAccardion.value[index];
};

const addTab = () => {
  if (!props.product.tabs) {
    props.product.tabs = [];
  }
  props.product.tabs.push({
    title: 'Новая вкладка',
    content: [],
  });
};

const removeTab = (tabIndex: number) => {
  props.product.tabs?.splice(tabIndex, 1);
};

const addDescriptionItem = (tabIndex: number) => {
  props.product.tabs?.[tabIndex].content.push({
    title: 'Новый пункт',
    description: '',
    "path-icon": '',
  });
};

const removeDescriptionItem = (tabIndex: number, itemIndex: number) => {
  props.product.tabs?.[tabIndex].content.splice(itemIndex, 1);
};
</script>

<template>
  <div class="tabs-editor-header">
    <label class="tabs-editor-header-label">
      Вкладки
      <MyBtn
        variant="primary"
        @click="toggleAccardion(0)"
        class="btn-toggle-accardion"
      >
        {{ openAccardion[0] ? 'Свернуть' : 'Развернуть' }}
      </MyBtn>
    </label>
  </div>
  <MyTransition>
    <div class="tabs-editor" v-if="product && openAccardion[0]">
      <div
        v-for="(tab, tabIndex) in product.tabs"
        :key="tabIndex"
        class="tab-item"
      >
        <div>
          <div class="tab-header">
            <div class="form-group">
              <label>Заголовок вкладки:</label>
              <input type="text" :value="tab.title" @input="$emit('update-tab-title', tabIndex, $event.target.value)" />
            </div>
            <div class="tab-header-buttons">
              <MyBtn
                variant="secondary"
                @click="toggleTab(tabIndex)"
                class="btn-toggle-tab"
              >
                {{ openTabs[tabIndex] ? 'Свернуть' : 'Развернуть' }}
              </MyBtn>
              <MyBtn
                variant="primary"
                @click="removeTab(tabIndex)"
                class="btn-delete-tab"
              >
                Удалить вкладку
              </MyBtn>
            </div>
          </div>

          <MyTransition>
            <div v-if="openTabs[tabIndex]">
              <div class="description-items-list">
                <h4>Пункты описания:</h4>
                <div
                  v-for="(item, itemIndex) in tab.content"
                  :key="itemIndex"
                  class="description-item"
                >
                  <!-- Заголовок элемента и кнопки управления всегда видимы -->
                  <div class="description-item-header" style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                    <div class="description-item-title" style="font-weight:bold;">{{ item.title || 'Без названия' }}</div>
                    <div style="display:flex; gap:8px;">
                      <MyBtn variant="secondary" @click="toggleItem(tabIndex, itemIndex)" class="btn-toggle-item">
                        {{ openItems[`${tabIndex}_${itemIndex}`] ? 'Свернуть' : 'Развернуть' }}
                      </MyBtn>
                      <MyBtn variant="primary" @click="removeDescriptionItem(tabIndex, itemIndex)" class="btn-delete-item">
                        Удалить
                      </MyBtn>
                    </div>
                  </div>
                  <MyTransition>
                    <div v-if="openItems[`${tabIndex}_${itemIndex}`] !== false" class="description-item-inputs">
                      <div class="form-group">
                        <label>Заголовок пункта:</label>
                        <input type="text" :value="item.title" @input="$emit('update-item', tabIndex, itemIndex, 'title', $event.target.value)" />
                      </div>
                      <div class="form-group">
                        <label>Иконка:</label>
                        <div class="icon-management">
                          <img
                            v-if="item['path-icon']"
                            :src="item['path-icon']"
                            alt="Иконка"
                            class="icon-preview"
                             width="100"
                             height="100"
                          />
                          <!-- <input
                            type="text"
                            v-model="item['path-icon']"
                            readonly
                            class="icon-input"
                          /> -->
                          <MyBtn
                            variant="secondary"
                            @click="$emit('upload-icon', tabIndex, itemIndex)"
                            class="btn-upload-image"
                          >
                            Загрузить новую
                          </MyBtn>
                          <MyBtn
                            variant="primary"
                            @click="$emit('delete-icon', tabIndex, itemIndex)"
                            class="btn-delete-item"
                            v-if="item['path-icon']"
                          >
                            Удалить
                          </MyBtn>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Описание пункта:</label>
                        <MyQuill :content="item.description" @update:content="$emit('update-item', tabIndex, itemIndex, 'description', $event)" />
                      </div>
                    </div>
                  </MyTransition>
                </div>
              </div>
              <MyBtn
                variant="secondary"
                @click="addDescriptionItem(tabIndex)"
                class="btn-add"
              >
                Добавить пункт описания
              </MyBtn>
            </div>
          </MyTransition>
        </div>
      </div>

      <MyBtn variant="secondary" @click="addTab()" class="btn-add btn-add-tab">
        Добавить вкладку
      </MyBtn>
    </div></MyTransition
  >
</template>

<style scoped>
.tabs-editor-header {
  display: flex;
  align-items: center;
  font-size: 28px;
}
.tabs-editor-header-label {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-weight: bold;
  font-size: 28px;
  flex-grow: 1;
  width: 100%;
  gap: 10px;
}
.btn-toggle-accardion {
  transform: scale(0.9);
}
.tabs-editor {
  border: 1px solid #555;
  padding: 15px;
  margin-top: 15px;
  border-radius: 5px;
  background-color: inherit;
}
.tab-item {
  border: 1px solid #4a4a4a;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 5px;
  background-color: inherit;
}
.tab-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}
.tab-header-buttons {
  display: flex;
  gap: 20px;
  transform: scale(0.9);
}
.tab-header .form-group {
  flex-grow: 1;
  margin-bottom: 0;
}
.description-items-list {
  padding-left: 15px;
  margin-bottom: 15px;
}
.description-item {
  display: flex;
  flex-direction: column;
  gap: 10px;
  border: 1px dashed #555;
  padding: 10px;
  margin-top: 10px;
  border-radius: 4px;
}

.description-item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.description-item-title {
  font-weight: bold;
}

.description-item-inputs {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.form-group {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 10px;
}
.form-group label {
  font-weight: bold;
  color: #ccc;
  min-width: 150px;
  font-size: 18px;
  padding-top: 8px;
}
.form-group input,
.form-group textarea {
  flex-grow: 1;
  padding: 8px;
  background-color: white;
  border: 1px solid #555;
  color: black;
  border-radius: 4px;
  font-size: 18px;
}
.form-group textarea {
  resize: none;
  overflow-y: hidden;
}
.icon-management {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}
.icon-preview {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 4px;
  border: 1px solid #666;
}
.icon-input {
  flex: 1;
  min-width: 200px;
}

.btn-add {
  width: 100%;
  max-width: 100%;
}
.btn-add-tab {
  width: 100%;
  margin-top: 15px;
}
.btn-upload-image {
  transform: scale(0.9);
}

.btn-delete-item {
  transform: scale(0.9);
}
.btn-delete-icon {
  background-color: #ffc107;
  color: black;
}
.btn-select-icon {
  background-color: #6c757d;
  border: 2px solid transparent;
}
.btn-select-icon.active {
  border-color: #00ff00;
  box-shadow: 0 0 5px #00ff00;
}

.my-quill-wrapper {
  width: 100%;
}

:deep(.ql-toolbar) {
  background-color: inherit;
}

:deep(.ql-container) {
  border-bottom: 1px solid #555;
  border-left: 1px solid #555;
  border-right: 1px solid #555;
  background-color: white;
  color: black;
}

:deep(.ql-editor) {
  color: black;
  min-height: 120px;
}
</style>
