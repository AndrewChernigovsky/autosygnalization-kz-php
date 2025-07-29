<script setup lang="ts">
import { useProductEditorStore } from '../../stores/productEditorStore';

const editorStore = useProductEditorStore();

defineOptions({
  name: 'Tabs',
});
</script>

<template>
  <div class="tabs-editor" v-if="editorStore.editingProduct">
    <div
      v-for="(tab, tabIndex) in editorStore.editingProduct.tabs"
      :key="tabIndex"
      class="tab-item"
    >
      <div class="tab-header">
        <div class="form-group">
          <label>Заголовок вкладки:</label>
          <input type="text" v-model="tab.title" />
        </div>
        <button @click="editorStore.removeTab(tabIndex)" class="btn-delete-tab">
          Удалить вкладку
        </button>
      </div>

      <div class="description-items-list">
        <h4>Пункты описания:</h4>
        <div
          v-for="(item, itemIndex) in tab.content"
          :key="itemIndex"
          class="description-item"
        >
          <div class="description-item-inputs">
            <div class="form-group">
              <label>Заголовок пункта:</label>
              <input type="text" v-model="item.title" />
            </div>
            <div class="form-group">
              <label>Иконка:</label>
              <input type="text" v-model="item.icon" />
            </div>
            <div class="form-group">
              <label>Описание пункта:</label>
              <textarea v-model="item.description"></textarea>
            </div>
          </div>
          <button
            @click="editorStore.removeDescriptionItem(tabIndex, itemIndex)"
            class="btn-delete-item"
          >
            Удалить пункт
          </button>
        </div>
      </div>
      <button @click="editorStore.addDescriptionItem(tabIndex)" class="btn-add">
        Добавить пункт описания
      </button>
    </div>

    <button @click="editorStore.addTab()" class="btn-add btn-add-tab">
      Добавить вкладку
    </button>
  </div>
</template>

<style scoped>
.tabs-editor {
  border: 1px solid #555;
  padding: 15px;
  margin-top: 15px;
  border-radius: 5px;
  background-color: #3a3a3a;
}
.tab-item {
  border: 1px solid #4a4a4a;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 5px;
  background-color: #404040;
}
.tab-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}
.tab-header .form-group {
  flex-grow: 1;
  margin-bottom: 0;
}
.description-items-list {
  padding-left: 15px;
  border-left: 2px solid #555;
  margin-bottom: 15px;
}
.description-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  border: 1px dashed #555;
  padding: 10px;
  margin-top: 10px;
  border-radius: 4px;
}
.description-item-inputs {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.form-group {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}
.form-group label {
  font-weight: bold;
  color: #ccc;
  min-width: 150px;
}
.form-group input,
.form-group textarea {
  flex-grow: 1;
  padding: 8px;
  background-color: #444;
  border: 1px solid #555;
  color: #fff;
  border-radius: 4px;
}
.btn-delete-tab,
.btn-delete-item,
.btn-add {
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  color: white;
  cursor: pointer;
  white-space: nowrap;
}
.btn-delete-tab,
.btn-delete-item {
  background-color: #c82333;
  align-self: center;
}
.btn-add {
  background-color: #007bff;
}
.btn-add-tab {
  width: 100%;
  margin-top: 15px;
}
</style>
