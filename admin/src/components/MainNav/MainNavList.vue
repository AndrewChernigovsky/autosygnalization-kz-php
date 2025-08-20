<script setup lang="ts">
import { ref, computed } from 'vue';
import mainNavStore from '../../stores/mainNavStore';
import MyCheckboxInput from '../UI/MyCheckboxInput.vue';
import MyBtn from '../UI/MyBtn.vue';
import MyInput from '../UI/MyInput.vue';
import MyFileInput from '../UI/MyFileInput.vue';
import PagesList from './PagesList.vue';
import updateNavItemOnDB from '../../functions/updateNavItemOnDB';
import deleteNavItemOnDB from '../../functions/deleteNavItemOnDB';
import fetchWithCors from '../../utils/fetchWithCors';
import showSwal from '../../functions/showSwal';
import DraggableList from '../UI/DraggableList.vue';

const store = mainNavStore();

const openNavItems = ref<Record<number, boolean>>({});

// Состояние для редактирования ссылок
const editingExternalLink = ref<Record<number, boolean>>({});

// Computed для сортированных элементов навигации
const sortedNavItems = computed(() => {
  return store.navItems.slice().sort((a, b) => a.sort_order - b.sort_order);
});

const handleFileChange = (file: File | null, navItem: any) => {
  navItem.icon_path = file;
};

const getImagePath = (iconPath: string | File | null): string => {
  if (!iconPath) return '';
  if (typeof iconPath === 'string') return iconPath;
  if (iconPath instanceof File) return URL.createObjectURL(iconPath);
  return '';
};

const toggleNavItem = (navItemId: number) => {
  // Если текущий элемент уже открыт, закрываем его
  if (openNavItems.value[navItemId]) {
    openNavItems.value[navItemId] = false;
  } else {
    // Закрываем все элементы
    Object.keys(openNavItems.value).forEach((key) => {
      openNavItems.value[Number(key)] = false;
    });
    // Открываем только выбранный элемент
    openNavItems.value[navItemId] = true;
  }
};

const isExternalLink = (link: string): boolean => {
  return (
    link.startsWith('http://') ||
    link.startsWith('https://') ||
    link.startsWith('www.')
  );
};

const toggleLinkType = (navItem: any) => {
  const isExternal =
    editingExternalLink.value[navItem.id] ?? isExternalLink(navItem.link);
  editingExternalLink.value[navItem.id] = !isExternal;

  if (!isExternal) {
    // Переключаем на внешнюю ссылку - очищаем поле для ввода
    navItem.link = '';
  } else {
    // Переключаем на внутреннюю ссылку - очищаем поле
    navItem.link = '';
  }
};

const handleSaveNavItem = async (navItem: any) => {
  if (!navItem.title.trim()) {
    showSwal('Ошибка', 'Заголовок не может быть пустым', 'error');
    return;
  }

  if (!navItem.link.trim()) {
    showSwal('Ошибка', 'Ссылка не может быть пустой', 'error');
    return;
  }

  await updateNavItemOnDB(navItem, store.API_BASE_URL);
  await store.getNavItems();
};

const handleDeleteNavItem = async (navItem: any) => {
  await deleteNavItemOnDB(navItem, store.API_BASE_URL);
  await store.getNavItems();
};

const handleReorder = async (reorderedNavItems: any[]) => {
  try {
    const updateData = reorderedNavItems.map((navItem, index) => ({
      id: navItem.id,
      sort_order: index + 1,
    }));

    // Optimistic update
    store.updateNavItemsOrder(updateData);

    // Send to server
    const response = await fetchWithCors(
      `${store.API_BASE_URL}?action=update-sort_order`,
      {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(updateData),
      }
    );

    if (response.success) {
      showSwal('Успешно', 'Порядок обновлен', 'success');
    } else {
      console.error('Ошибка обновления порядка:', response.error);
      showSwal('Ошибка', 'Не удалось обновить порядок', 'error');
      await store.getNavItems(); // Revert on error
    }
  } catch (error) {
    console.error('Ошибка при изменении порядка навигации:', error);
    showSwal('Ошибка', 'Произошла ошибка при изменении порядка', 'error');
    await store.getNavItems(); // Revert on error
  }
};
</script>

<template>
  <div class="nav-list-wrapper">
    <DraggableList
      :model-value="sortedNavItems"
      item-key="id"
      tag="ul"
      class="nav-list list-style-none m-0 p-0"
      @reorder="handleReorder"
    >
      <template #item="{ item, dragHandleProps, isDragOver }">
        <li class="nav-item" :class="{ 'drag-over': isDragOver }">
          <div class="nav-item-header">
            <div class="nav-item-header-btn drag-btn" v-bind="dragHandleProps">
              <img
                style="display: block; margin-right: 10px"
                src="./../../assets/d-and-d.svg"
                alt=""
                width="40"
                height="40"
              />
            </div>
            <div class="nav-item-header-wrapper">
              <h2 class="title m-0">
                {{ item.title ? item.title : 'Заголовок' }}
              </h2>
              <MyBtn variant="primary" @click="toggleNavItem(item.id)">{{
                openNavItems[item.id] ? 'Закрыть' : 'Редактировать'
              }}</MyBtn>
            </div>
          </div>
          <div
            class="nav-item-wrapper"
            :class="{ active: openNavItems[item.id] }"
          >
            <div class="nav-item-content">
              <div class="nav-input-wrapper">
                <h3 class="subtitle m-0">Заголовок*</h3>
                <MyInput variant="primary" v-model="item.title" />
              </div>
              <div class="nav-input-wrapper">
                <h3 class="subtitle m-0">Контент на странице</h3>
                <MyInput variant="primary" v-model="item.content" />
              </div>
              <div class="nav-checkbox-wrapper">
                <div class="nav-checkbox-title">
                  <h3>Тип ссылки</h3>
                </div>
                <label>
                  <span>Внешняя ссылка</span>
                  <MyCheckboxInput
                    variant="primary"
                    :model-value="
                      editingExternalLink[item.id] ?? isExternalLink(item.link)
                    "
                    :value="true"
                    @update:model-value="toggleLinkType(item)"
                  />
                  <span>Внутренняя страница</span>
                </label>
              </div>
              <div
                class="nav-link-wrapper"
                v-if="
                  !(editingExternalLink[item.id] ?? isExternalLink(item.link))
                "
              >
                <div class="nav-pages-title">
                  <h3 class="subtitle m-0">Выберите страницу*</h3>
                </div>
                <PagesList :navItem="item" />
              </div>
              <div
                class="nav-input-wrapper"
                v-if="editingExternalLink[item.id] ?? isExternalLink(item.link)"
              >
                <h3 class="subtitle m-0">Внешняя ссылка*</h3>
                <MyInput variant="primary" v-model="item.link" />
              </div>
              <div class="nav-input-wrapper">
                <h3 class="subtitle m-0">Изображение</h3>
                <div class="nav-item-image-wrapper">
                  <MyFileInput
                    :imgPath="getImagePath(item.icon_path)"
                    :accept="'image/svg+xml'"
                    variant="primary"
                    @file-change="(file) => handleFileChange(file, item)"
                  />
                </div>
              </div>
              <div class="nav-input-wrapper">
                <h3 class="subtitle m-0">Показывать на странице</h3>
                <div class="checkbox-wrapper">
                  <p class="m-0">Да</p>
                  <MyCheckboxInput
                    variant="primary"
                    v-model="item.on_page"
                    :value="true"
                  />
                  <p>Нет</p>
                </div>
              </div>
              <div class="btn-wrapper">
                <MyBtn variant="primary" @click="handleSaveNavItem(item)"
                  >Сохранить</MyBtn
                >
                <MyBtn variant="primary" @click="handleDeleteNavItem(item)"
                  >Удалить</MyBtn
                >
              </div>
            </div>
          </div>
        </li>
      </template>
    </DraggableList>
  </div>
</template>

<style scoped>
.nav-list-wrapper {
  width: 100%;
}

.nav-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.nav-item {
  padding: 20px;
  display: flex;
  flex-direction: column;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  width: 100%;
}

.nav-item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-item-wrapper {
  display: grid;
  grid-template-rows: 0fr;
  transition: all 0.3s ease;

  &.active {
    margin-top: 10px;
    grid-template-rows: 1fr;
  }
}

.nav-item-content {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.nav-input-wrapper {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.checkbox-wrapper {
  display: flex;
  align-items: center;
  gap: 10px;
}

.nav-item-header-wrapper {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-checkbox-wrapper {
  & label {
    display: flex;
    gap: 10px;
    align-items: center;
  }
}

.nav-link-wrapper {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.nav-pages-title {
  margin-bottom: 10px;
}

.btn-wrapper {
  display: flex;
  gap: 10px;
  align-items: center;
}

/* Стили для drag-and-drop */
.nav-item {
  transition: all 0.2s ease;
}
.nav-item.drag-over {
  border-color: #007bff;
  background-color: rgba(0, 123, 255, 0.1);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}
.nav-item-image-wrapper {
  width: 150px;
  height: 150px;
}

.drag-btn {
  display: flex;
  align-items: center;
  cursor: grab;

  &:active {
    cursor: grabbing;
  }

  img {
    transition: transform 0.2s ease;
    pointer-events: none;

    &:hover {
      transform: scale(1.1);
    }
  }

  &:hover img {
    transform: scale(1.1);
  }

  &:active img {
    transform: scale(0.95);
  }
}
</style>
