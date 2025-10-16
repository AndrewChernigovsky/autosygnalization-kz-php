<script setup lang="ts">
import { ref, watch } from 'vue';
import MyBtn from '../../UI/MyBtn.vue';
import MyTransition from '../../UI/MyTransition.vue';
import MyQuill from '../../UI/MyQuill.vue';
import type { ProductI } from '../interfaces/Products';

defineOptions({
  name: 'Tabs',
});

const emit = defineEmits(['upload-icon', 'delete-icon', 'tabs-changed']);

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
        tab.content.forEach((_, iIdx: number) => {
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
    description:  '',
    "path-icon": '',
  });

  console.log(props.product.tabs, 'PRODUCTS ADD DESCRIPTION ITEM');
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
              <input type="text" v-model="tab.title" />
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
                        <input type="text" v-model="item.title" required/>
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
                        <MyQuill v-model:content="item.description" />
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

      <MyBtn variant="therdary" @click="addTab()" class="btn-add btn-add-tab">
        Добавить вкладку
      </MyBtn>
    </div></MyTransition
  >
</template>

<style scoped>
@import './Tabs.module.scss';

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
