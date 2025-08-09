<script setup lang="ts">
import { ref } from 'vue';
import mainNavStore from '../../stores/mainNavStore';
import MyBtn from '../UI/MyBtn.vue';
import MyInput from '../UI/MyInput.vue';
import MyFileInput from '../UI/MyFileInput.vue';
import MyCheckboxInput from '../UI/MyCheckboxInput.vue';
import addItemOnDB from '../../functions/addItemOnDB';
import showSwal from '../../functions/showSwal';
import PagesList from './PagesList.vue';

const addNewNavActive = ref(false);

const store = mainNavStore();

const handleFileChange = (file: File | null) => {
  store.newNavItem.icon_path = file;
};

const addNewNavItem = async (e?: Event | null) => {
  store.isValid = false;

  if (!store.newNavItem.title || !store.newNavItem.link) {
    store.isValid = false;
  } else {
    store.isValid = true;
  }

  const targetElement = document.getElementById('pages-list');

  if (!store.isValid) {
    if (e) {
      (e.target as HTMLElement).blur();
    }
    showSwal('Ошибка', 'Заполните все поля', 'error');
    if (targetElement && !store.newNavItem.isExternal) {
      targetElement.scrollIntoView({
        behavior: 'smooth',
        block: 'center',
      });
    }

    return;
  }

  store.newNavItem.sort_order = store.navItems.length + 1;

  await addItemOnDB(store.newNavItem, store.API_BASE_URL);
  addNewNavActive.value = false;
  store.getNavItems();
  store.resetNewNavItem();
};
</script>

<template>
  <div class="addlink">
    <div class="addlink-header">
      <h2 class="title m-0">Добавить новый элемент навигации</h2>
      <MyBtn variant="primary" @click="addNewNavActive = !addNewNavActive">{{
        addNewNavActive ? 'Закрыть' : 'Добавить'
      }}</MyBtn>
    </div>
    <div class="addlink-body-wrapper" :class="{ active: addNewNavActive }">
      <div class="addlink-body-content">
        <div class="addlink-inputs-wrapper">
          <div class="addlink-label">
            <h3 class="subtitle m-0">Заголовок*</h3>
            <MyInput
              :class="store.isValid ? '' : 'not-valid'"
              variant="primary"
              v-model="store.newNavItem.title"
            />
            <p class="addlink-help m-0">
              *Введите заголовок навигационного элемента
            </p>
          </div>
          <div class="addlink-label">
            <h3 class="subtitle m-0">Контент на странице</h3>
            <MyInput variant="primary" v-model="store.newNavItem.content" />
            <p class="addlink-help m-0">
              *Дополнительный контент для навигационного элемента (опционально)
            </p>
          </div>
          <div class="addlink-checkbox-wrapper">
            <div class="addlink-checkbox-title">
              <h3>Тип ссылки</h3>
            </div>
            <label>
              <span>Внешняя ссылка</span>
              <MyCheckboxInput
                variant="primary"
                v-model="store.newNavItem.isExternal"
                :value="true"
              />
              <span>Внутренняя страница</span>
            </label>
          </div>
          <div class="addlink-link-wrapper" v-if="!store.newNavItem.isExternal">
            <div class="addlink-pages-title">
              <h3 class="subtitle m-0">Выберите страницу*</h3>
            </div>
            <PagesList
              id="pages-list"
              class="addlink-pages-list list-style-none m-0 p-0"
              :class="store.isValid ? '' : 'not-valid'"
            />
          </div>
          <div class="addlink-label" v-if="store.newNavItem.isExternal">
            <h3 class="subtitle m-0">Внешняя ссылка*</h3>
            <MyInput
              :class="store.isValid ? '' : 'not-valid'"
              variant="primary"
              v-model="store.newNavItem.link"
            />
            <p class="addlink-help m-0">
              *Введите полную внешнюю ссылку (например: https://example.com)
            </p>
          </div>
          <div class="addlink-label file-input">
            <h3 class="subtitle m-0">Иконка</h3>
            <div class="file-input-wrapper">
              <MyFileInput
                :accept="'image/svg+xml'"
                @file-change="handleFileChange"
              />
            </div>
            <p class="addlink-help m-0">
              *Иконки должны быть в формате SVG, не более 50кб.
            </p>
          </div>
        </div>
        <div class="addlink-checkbox-wrapper">
          <div class="addlink-checkbox-title">
            <h3>Показывать на странице</h3>
          </div>
          <label>
            <span>Да</span>
            <MyCheckboxInput
              variant="primary"
              v-model="store.newNavItem.on_page"
              :value="true"
            />
            <span>Нет</span>
          </label>
        </div>
        <div class="addlink-buttons-wrapper">
          <MyBtn type="button" variant="primary" @click="addNewNavItem($event)"
            >Добавить</MyBtn
          >
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.addlink {
  display: flex;
  flex-direction: column;
  padding: 20px;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  width: 100%;
}

.addlink-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  overflow: hidden;
}

.addlink-body-wrapper {
  display: grid;
  grid-template-rows: 0fr;
  transition: all 0.3s ease;

  &.active {
    margin-top: 20px;
    grid-template-rows: 1fr;
  }
}

.addlink-body-content {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.addlink-pages-title {
  margin-bottom: 10px;
}

.addlink-pages-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  padding: 20px;
}

.addlink-inputs-wrapper {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.addlink-label {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.file-input-wrapper {
  width: 150px;
  height: 150px;
}

.addlink-help {
  font-size: 12px;
}

.addlink-checkbox-wrapper {
  & label {
    display: flex;
    gap: 10px;
    align-items: center;
  }
}

.addlink-buttons-wrapper {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

.addlink-link-wrapper {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
</style>
