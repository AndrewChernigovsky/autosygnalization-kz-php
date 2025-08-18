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

const store = mainNavStore();

const openNavItems = ref<Record<number, boolean>>({});

// Состояние для drag-and-drop
const draggedItem = ref<any>(null);
const dragOverItem = ref<any>(null);

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

// Функции для drag-and-drop
const handleDragStart = (event: DragEvent, navItem: any) => {
  draggedItem.value = navItem;
  if (event.dataTransfer) {
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', navItem.id.toString());
  }
};

const handleDragOver = (event: DragEvent, navItem: any) => {
  event.preventDefault();
  if (event.dataTransfer) {
    event.dataTransfer.dropEffect = 'move';
  }
  dragOverItem.value = navItem;
};

const handleDragLeave = () => {
  dragOverItem.value = null;
};

// Добавляем новую функцию для завершения drag-операции
const handleDragEnd = () => {
  // Принудительно сбрасываем все состояния при завершении drag
  draggedItem.value = null;
  dragOverItem.value = null;
};

const handleDrop = async (event: DragEvent, targetNavItem: any) => {
  event.preventDefault();

  if (!draggedItem.value || draggedItem.value.id === targetNavItem.id) {
    draggedItem.value = null;
    dragOverItem.value = null;
    return;
  }

  await reorderNavItems(draggedItem.value, targetNavItem);

  draggedItem.value = null;
  dragOverItem.value = null;
};

const reorderNavItems = async (draggedNavItem: any, targetNavItem: any) => {
  try {
    // Получаем все элементы навигации, отсортированные по sort_order
    const navItemsArray = sortedNavItems.value;

    // Находим индексы перетаскиваемого и целевого элементов
    const draggedIndex = navItemsArray.findIndex(
      (n) => n.id === draggedNavItem.id
    );
    const targetIndex = navItemsArray.findIndex(
      (n) => n.id === targetNavItem.id
    );

    if (draggedIndex === -1 || targetIndex === -1) return;

    // Создаем новый массив с измененным порядком
    const reorderedNavItems = [...navItemsArray];
    const [removed] = reorderedNavItems.splice(draggedIndex, 1);
    reorderedNavItems.splice(targetIndex, 0, removed);

    // Обновляем sort_order для всех элементов навигации
    const updateData = reorderedNavItems.map((navItem, index) => ({
      id: navItem.id,
      sort_order: index + 1,
    }));

    // Отправляем обновление на сервер
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
      // Используем функцию store для корректного обновления реактивности
      store.updateNavItemsOrder(updateData);

      showSwal('Успешно', 'Порядок обновлен', 'success');
    } else {
      console.error('Ошибка обновления порядка:', response.error);
      showSwal('Ошибка', 'Не удалось обновить порядок', 'error');
    }
  } catch (error) {
    console.error('Ошибка при изменении порядка навигации:', error);
    showSwal('Ошибка', 'Произошла ошибка при изменении порядка', 'error');
  }
};
</script>

<template>
  <div class="nav-list-wrapper">
    <ul class="nav-list list-style-none m-0 p-0">
      <li
        class="nav-item"
        v-for="navItem in sortedNavItems"
        :key="navItem.id"
        :class="{
          dragging: draggedItem?.id === navItem.id,
          'drag-over': dragOverItem?.id === navItem.id,
        }"
        @dragover="handleDragOver($event, navItem)"
        @dragleave="handleDragLeave"
        @drop="handleDrop($event, navItem)"
        @dragend="handleDragEnd"
      >
        <div class="nav-item-header">
          <div
            class="nav-item-header-btn drag-btn"
            draggable="true"
            @dragstart="handleDragStart($event, navItem)"
            @dragend="handleDragEnd"
          >
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
              {{ navItem.title ? navItem.title : 'Заголовок' }}
            </h2>
            <MyBtn variant="primary" @click="toggleNavItem(navItem.id)">{{
              openNavItems[navItem.id] ? 'Закрыть' : 'Редактировать'
            }}</MyBtn>
          </div>
        </div>
        <div
          class="nav-item-wrapper"
          :class="{ active: openNavItems[navItem.id] }"
        >
          <div class="nav-item-content">
            <div class="nav-input-wrapper">
              <h3 class="subtitle m-0">Заголовок*</h3>
              <MyInput variant="primary" v-model="navItem.title" />
            </div>
            <div class="nav-input-wrapper">
              <h3 class="subtitle m-0">Контент на странице</h3>
              <MyInput variant="primary" v-model="navItem.content" />
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
                    editingExternalLink[navItem.id] ??
                    isExternalLink(navItem.link)
                  "
                  :value="true"
                  @update:model-value="toggleLinkType(navItem)"
                />
                <span>Внутренняя страница</span>
              </label>
            </div>
            <div
              class="nav-link-wrapper"
              v-if="
                !(
                  editingExternalLink[navItem.id] ??
                  isExternalLink(navItem.link)
                )
              "
            >
              <div class="nav-pages-title">
                <h3 class="subtitle m-0">Выберите страницу*</h3>
              </div>
              <PagesList :navItem="navItem" />
            </div>
            <div
              class="nav-input-wrapper"
              v-if="
                editingExternalLink[navItem.id] ?? isExternalLink(navItem.link)
              "
            >
              <h3 class="subtitle m-0">Внешняя ссылка*</h3>
              <MyInput variant="primary" v-model="navItem.link" />
            </div>
            <div class="nav-input-wrapper">
              <h3 class="subtitle m-0">Изображение</h3>
              <div class="nav-item-image-wrapper">
                <MyFileInput
                  :imgPath="getImagePath(navItem.icon_path)"
                  :accept="'image/svg+xml'"
                  variant="primary"
                  @file-change="(file) => handleFileChange(file, navItem)"
                />
              </div>
            </div>
            <div class="nav-input-wrapper">
              <h3 class="subtitle m-0">Показывать на странице</h3>
              <div class="checkbox-wrapper">
                <p class="m-0">Да</p>
                <MyCheckboxInput
                  variant="primary"
                  v-model="navItem.on_page"
                  :value="true"
                />
                <p>Нет</p>
              </div>
            </div>
            <div class="btn-wrapper">
              <MyBtn variant="primary" @click="handleSaveNavItem(navItem)"
                >Сохранить</MyBtn
              >
              <MyBtn variant="primary" @click="handleDeleteNavItem(navItem)"
                >Удалить</MyBtn
              >
            </div>
          </div>
        </div>
      </li>
    </ul>
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

  &.dragging {
    opacity: 0.5;
    transform: scale(0.95);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  }

  &.drag-over {
    border-color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
  }

  &:hover:not(.dragging) {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }
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
