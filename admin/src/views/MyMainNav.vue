<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import mainNavStore from '../stores/mainNavStore';
import LoadingModal from '../components/UI/LoadingModal.vue';
import MyInput from '../components/UI/MyInput.vue';
import MyBtn from '../components/UI/MyBtn.vue';
import Swal from 'sweetalert2';

interface INavItem {
  id?: number;
  title: string;
  link: string;
  icon_path: File | string | null;
  icon_path_url?: string | null;
  order?: number;
  isActive?: boolean;
}

const API_BASE_URL = '/server/php/admin/api/navigation/navigation.php';

const store = mainNavStore();

const navItems = ref<INavItem[]>([]);

const newNavItem = ref<INavItem>({
  title: '',
  link: '',
  icon_path: null,
  icon_path_url: null,
});

const isLoading = ref(false);
const error = ref<string | null>(null);
const activeEditItem = ref<INavItem | null>(null);
const isExternalLink = ref(false);
const selectedPageLink = ref('');
const editItemExternalLink = ref<Record<number, boolean>>({});
const editItemSelectedPage = ref<Record<number, string>>({});

// Добавляем переменные для drag and drop
const draggedItem = ref<INavItem | null>(null);
const dragOverItem = ref<INavItem | null>(null);

onMounted(async () => {
  await getNavItems();
  await getAvailablePages();
});

const getAvailablePages = async () => {
  await store.getAvailablePages();
};

const getNavItems = async () => {
  isLoading.value = true;
  try {
    await store.getNavItems(`${API_BASE_URL}`);
    navItems.value = store.navItems;
    error.value = store.error;
  } catch (err) {
    error.value = 'Ошибка загрузки данных';
  } finally {
    isLoading.value = false;
  }
};

const addNavItem = async () => {
  if (!newNavItem.value.title.trim()) {
    Swal.fire('Ошибка!', 'Заголовок обязателен для заполнения', 'error');
    return;
  }

  if (!newNavItem.value.link.trim()) {
    Swal.fire('Ошибка!', 'Ссылка обязательна для заполнения', 'error');
    return;
  }

  await store.addNavItem(API_BASE_URL, newNavItem.value);
  resetNewNavItem();
  await getNavItems();
};

const updateNavItem = async (item: INavItem) => {
  if (!item.title?.trim()) {
    Swal.fire('Ошибка!', 'Заголовок обязателен для заполнения', 'error');
    return;
  }

  if (!item.link?.trim()) {
    Swal.fire('Ошибка!', 'Ссылка обязательна для заполнения', 'error');
    return;
  }

  await store.updateNavItem(API_BASE_URL, item.id as number, item);
  activeEditItem.value = null;

  // Очищаем состояние редактирования после успешного обновления
  if (item.id) {
    delete editItemExternalLink.value[item.id];
    delete editItemSelectedPage.value[item.id];
  }

  await getNavItems();
};

const deleteNavItem = async (item: INavItem) => {
  const result = await Swal.fire({
    title: 'Вы уверены?',
    text: 'Это действие нельзя отменить!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
  });

  if (result.isConfirmed) {
    await store.deleteNavItem(API_BASE_URL, item.id as number);
    await getNavItems();
  }
};

const updateNavItemsOrder = async (
  orderData: { id: number; order: number }[]
) => {
  try {
    const response = await fetch(`${API_BASE_URL}?action=update-order`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(orderData),
    });

    const result = await response.json();

    if (!result.success) {
      Swal.fire(
        'Ошибка!',
        'Не удалось обновить порядок элементов навигации',
        'error'
      );
    }
  } catch (error) {
    Swal.fire('Ошибка!', 'Не удалось отправить запрос на сервер', 'error');
  }
};

const handleRetry = async () => {
  await getNavItems();
};

const toggleEditItem = (item: INavItem) => {
  if (activeEditItem.value === item) {
    activeEditItem.value = null;
    // Очищаем состояние редактирования при закрытии
    if (item.id) {
      delete editItemExternalLink.value[item.id];
      delete editItemSelectedPage.value[item.id];
    }
  } else {
    activeEditItem.value = item;

    // Инициализируем состояние toggle на основе текущей ссылки
    if (item.id) {
      const isExternal =
        item.link &&
        (item.link.startsWith('http') || item.link.startsWith('https'));
      editItemExternalLink.value[item.id] = !!isExternal;

      // Если это внутренняя страница, найдем соответствующую страницу в списке
      if (
        !isExternal &&
        store.availablePages &&
        Array.isArray(store.availablePages)
      ) {
        const matchingPage = store.availablePages.find(
          (page: any) =>
            (page.path && page.path === item.link) ||
            (page.link && page.link === item.link) ||
            (page.url && page.url === item.link)
        );
        if (matchingPage) {
          editItemSelectedPage.value[item.id] =
            (matchingPage as any).path ||
            (matchingPage as any).link ||
            (matchingPage as any).url ||
            '';
        } else {
          editItemSelectedPage.value[item.id] = '';
        }
      } else {
        editItemSelectedPage.value[item.id] = '';
      }
    }
  }
};

const handleEditFileChange = (file: File | null, item: INavItem) => {
  if (file) {
    item.icon_path = file;
    item.icon_path_url = URL.createObjectURL(file);
  }
};

const handleNewFileChange = (file: File | null) => {
  if (file) {
    newNavItem.value.icon_path = file;
    newNavItem.value.icon_path_url = URL.createObjectURL(file);
  }
};

const resetNewNavItem = () => {
  newNavItem.value = {
    title: '',
    link: '',
    icon_path: null,
    icon_path_url: null,
  };
  selectedPageLink.value = '';
};

const getImageUrl = (item: INavItem): string => {
  if (item.icon_path_url) return item.icon_path_url;
  if (typeof item.icon_path === 'string' && item.icon_path) {
    const fullPath = item.icon_path.startsWith('/')
      ? `http://localhost:5173${item.icon_path}`
      : item.icon_path;
    return fullPath;
  }
  return '';
};

// Методы для drag and drop
const handleDragStart = (event: DragEvent, item: INavItem) => {
  draggedItem.value = item;
  if (event.dataTransfer) {
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', item.id?.toString() || '');
  }
};

const handleDragOver = (event: DragEvent) => {
  event.preventDefault();
  if (event.dataTransfer) {
    event.dataTransfer.dropEffect = 'move';
  }
};

const handleDragEnter = (event: DragEvent, item: INavItem) => {
  event.preventDefault();
  dragOverItem.value = item;
};

const handleDragLeave = (event: DragEvent) => {
  // Проверяем, что мы действительно покидаем элемент, а не его дочерний элемент
  if (
    !(event.currentTarget as Element)?.contains(event.relatedTarget as Node)
  ) {
    dragOverItem.value = null;
  }
};

const handleDrop = async (event: DragEvent, targetItem: INavItem) => {
  event.preventDefault();

  if (!draggedItem.value || draggedItem.value.id === targetItem.id) {
    draggedItem.value = null;
    dragOverItem.value = null;
    return;
  }

  // Получаем элементы навигации
  const draggedIndex = navItems.value.findIndex(
    (item) => item.id === draggedItem.value?.id
  );
  const targetIndex = navItems.value.findIndex(
    (item) => item.id === targetItem.id
  );

  if (draggedIndex !== -1 && targetIndex !== -1) {
    // Создаем новый массив с измененным порядком
    const reorderedItems = [...navItems.value];
    const [movedItem] = reorderedItems.splice(draggedIndex, 1);
    reorderedItems.splice(targetIndex, 0, movedItem);

    // Обновляем порядковые номера
    const orderData: { id: number; order: number }[] = [];
    reorderedItems.forEach((item, index) => {
      const newOrder = index + 1;
      if (item.id) {
        orderData.push({
          id: item.id,
          order: newOrder,
        });
        // Обновляем локальные данные
        item.order = newOrder;
      }
    });

    // Обновляем основной массив navItems
    navItems.value = reorderedItems;

    // Отправляем изменения на сервер
    await updateNavItemsOrder(orderData);
  }

  draggedItem.value = null;
  dragOverItem.value = null;
};

const handleDragEnd = () => {
  draggedItem.value = null;
  dragOverItem.value = null;
};

const toggleLinkType = () => {
  isExternalLink.value = !isExternalLink.value;
  // Очищаем поле ссылки и выбранную страницу при переключении
  newNavItem.value.link = '';
  selectedPageLink.value = '';
};

// Следим за изменением выбранной страницы
watch(selectedPageLink, (newValue) => {
  if (newValue) {
    newNavItem.value.link = newValue;
  } else if (!isExternalLink.value) {
    // Если радиокнопка не выбрана и это внутренняя страница, очищаем ссылку
    newNavItem.value.link = '';
  }
});

// Функция переключения типа ссылки для редактирования
const toggleEditLinkType = (item: INavItem) => {
  if (!item.id) return;

  const itemId = item.id;
  editItemExternalLink.value[itemId] = !editItemExternalLink.value[itemId];

  // Очищаем поле ссылки и выбранную страницу при переключении
  item.link = '';
  editItemSelectedPage.value[itemId] = '';
};

// Следим за изменением выбранной страницы при редактировании
watch(
  editItemSelectedPage,
  (newValues) => {
    Object.keys(newValues).forEach((itemIdStr) => {
      const itemId = parseInt(itemIdStr);
      const selectedLink = newValues[itemId];
      const item = navItems.value.find((item) => item.id === itemId);

      if (item && selectedLink) {
        item.link = selectedLink;
      } else if (item && !editItemExternalLink.value[itemId]) {
        // Если радиокнопка не выбрана и это внутренняя страница, очищаем ссылку
        item.link = '';
      }
    });
  },
  { deep: true }
);
</script>

<template>
  <div class="my-page p-20">
    <section class="navigation">
      <h2 class="navigation-title my-title m-0">Основная навигация</h2>

      <LoadingModal
        v-if="isLoading || error"
        :is-loading="isLoading"
        :error="error"
        :retry="handleRetry"
      />

      <div v-if="!isLoading && !error" class="navigation-wrapper">
        <div class="navigation-header">
          <h3 class="navigation-subtitle">Элементы навигации</h3>
        </div>

        <div class="navigation-content">
          <div class="navigation-content-wrapper">
            <!-- Форма добавления -->
            <div class="add-nav-item">
              <div class="add-nav-item-label-wrapper">
                <label class="add-nav-item-label">
                  <span class="add-nav-item-label-text">Заголовок *</span>
                  <MyInput
                    type="text"
                    variant="primary"
                    v-model="newNavItem.title"
                    placeholder="Введите заголовок"
                  />
                </label>
                <div class="toggle-wrapper">
                  <div class="toggle-item">
                    <span class="toggle-item-text">Внутрення страница </span>
                  </div>
                  <div class="toggle-btn-wrapper">
                    <MyBtn
                      variant="primary"
                      class="toggle-btn"
                      :class="{ active: isExternalLink }"
                      @click="toggleLinkType"
                    >
                      <div class="toggle-btn-input"></div>
                    </MyBtn>
                  </div>
                  <div class="toggle-item">
                    <span class="toggle-item-text">Внешняя ссылка</span>
                  </div>
                </div>
                <label class="add-nav-item-label" v-if="isExternalLink">
                  <span class="add-nav-item-label-text">Ссылка *</span>
                  <MyInput
                    type="text"
                    variant="primary"
                    v-model="newNavItem.link"
                    placeholder="Например: https://example.com"
                  />
                </label>

                <div v-if="!isExternalLink" class="radio-pages-wrapper">
                  <span class="add-nav-item-label-text"
                    >Выберите страницу *</span
                  >
                  <label
                    v-for="page in store.availablePages as any"
                    :key="page.title"
                    class="radio-page-item"
                  >
                    <input
                      type="radio"
                      :value="page.path || page.link || page.url || ''"
                      v-model="selectedPageLink"
                      class="radio-page-input"
                    />
                    <span class="radio-page-text">{{ page.title }}</span>
                  </label>
                </div>

                <label class="add-nav-item-label file">
                  <span class="add-nav-item-label-text">Иконка</span>
                  <MyInput
                    width="150px"
                    height="150px"
                    type="file"
                    variant="primary"
                    :img="newNavItem.icon_path_url || ''"
                    @fileChange="handleNewFileChange"
                  />
                </label>
              </div>

              <MyBtn
                variant="primary"
                @click="addNavItem"
                :disabled="!newNavItem.title.trim() || !newNavItem.link.trim()"
              >
                Добавить
              </MyBtn>
            </div>

            <!-- Список элементов навигации -->
            <ul
              class="navigation-list list-style-none"
              v-if="navItems.length > 0"
            >
              <li
                class="navigation-item"
                :class="{
                  dragging: draggedItem?.id === item.id,
                  'drag-over': dragOverItem?.id === item.id,
                }"
                v-for="item in navItems"
                :key="item.id"
                @dragover="handleDragOver($event)"
                @dragenter="handleDragEnter($event, item)"
                @dragleave="handleDragLeave($event)"
                @drop="handleDrop($event, item)"
              >
                <div class="item-header">
                  <div class="item-header-wrapper">
                    <MyBtn
                      class="d-and-d-btn"
                      draggable="true"
                      @dragstart="handleDragStart($event, item)"
                      @dragend="handleDragEnd"
                    >
                      <img
                        width="20px"
                        height="20px"
                        src="/src/assets/d-and-d.svg"
                        alt="drag-and-drop"
                      />
                    </MyBtn>
                    <div class="item-info">
                      <h3 v-if="activeEditItem !== item">{{ item.title }}</h3>
                      <MyInput
                        v-if="activeEditItem === item"
                        type="text"
                        variant="primary"
                        v-model="item.title"
                        placeholder="Заголовок"
                      />
                      <span v-if="activeEditItem !== item" class="item-link">{{
                        item.link
                      }}</span>
                    </div>
                  </div>

                  <MyBtn variant="primary" @click="toggleEditItem(item)">
                    {{ activeEditItem === item ? 'Закрыть' : 'Редактировать' }}
                  </MyBtn>
                </div>

                <div
                  class="item-content"
                  :class="{ active: activeEditItem === item }"
                >
                  <div class="item-content-wrapper">
                    <div class="item-content-inputs">
                      <div class="toggle-wrapper">
                        <div class="toggle-item">
                          <span class="toggle-item-text"
                            >Внутрення страница
                          </span>
                        </div>
                        <div class="toggle-btn-wrapper">
                          <MyBtn
                            variant="primary"
                            class="toggle-btn"
                            :class="{ active: editItemExternalLink[item.id as number] }"
                            @click="toggleEditLinkType(item)"
                          >
                            <div class="toggle-btn-input"></div>
                          </MyBtn>
                        </div>
                        <div class="toggle-item">
                          <span class="toggle-item-text">Внешняя ссылка</span>
                        </div>
                      </div>

                      <label
                        class="add-nav-item-label"
                        v-if="editItemExternalLink[item.id as number]"
                      >
                        <span class="add-nav-item-label-text">Ссылка *</span>
                        <MyInput
                          type="text"
                          variant="primary"
                          v-model="item.link as string"
                          placeholder="Например: https://example.com"
                        />
                      </label>

                      <div
                        v-if="!editItemExternalLink[item.id as number]"
                        class="radio-pages-wrapper"
                      >
                        <span class="add-nav-item-label-text"
                          >Выберите страницу *</span
                        >
                        <label
                          v-for="page in store.availablePages as any"
                          :key="page.title"
                          class="radio-page-item"
                        >
                          <input
                            type="radio"
                            :value="page.path || page.link || page.url || ''"
                            v-model="editItemSelectedPage[item.id as number]"
                            class="radio-page-input"
                          />
                          <span class="radio-page-text">{{ page.title }}</span>
                        </label>
                      </div>

                      <label class="file">
                        <span class="add-nav-item-label-text">Иконка</span>
                        <MyInput
                          width="150px"
                          height="150px"
                          type="file"
                          variant="primary"
                          :img="getImageUrl(item)"
                          @fileChange="
                            (file) => handleEditFileChange(file, item)
                          "
                        />
                      </label>
                    </div>

                    <div class="item-content-footer">
                      <MyBtn
                        variant="primary"
                        @click="updateNavItem(item)"
                        :disabled="!item.title?.trim() || !item.link?.trim()"
                      >
                        Сохранить
                      </MyBtn>
                      <MyBtn variant="primary" @click="deleteNavItem(item)">
                        Удалить
                      </MyBtn>
                    </div>
                  </div>
                </div>
              </li>
            </ul>

            <div v-else class="empty-state">
              <p>Нет элементов навигации. Добавьте первый элемент выше.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.navigation {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.navigation-wrapper {
  padding: 16px;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: inset 0 0 0 1px #ffffff;
}

.navigation-header {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  margin-bottom: 16px;
}

.navigation-content-wrapper {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.add-nav-item {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.add-nav-item-label-wrapper {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.add-nav-item-label {
  display: flex;
  flex-direction: column;
  gap: 8px;

  &.file {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    max-width: 150px;
  }
}

.add-nav-item-label-text {
  font-weight: 500;
  color: #ffffff;
}

.navigation-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
  padding: 0;
  margin: 0;
}

.navigation-item {
  box-shadow: inset 0 0 0 1px #ffffff;
  padding: 16px;
  border-radius: 8px;
  transition: all 0.3s ease;

  &:hover {
    transform: translateY(-2px);
    box-shadow: inset 0 0 0 1px #ffffff, 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  &.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
    z-index: 1000;
  }

  &.drag-over {
    transform: translateY(-4px);
    box-shadow: inset 0 0 0 2px #007bff, 0 8px 16px rgba(0, 123, 255, 0.3);
    background-color: rgba(0, 123, 255, 0.05);
  }
}

.item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 30px;
}

.item-header-wrapper {
  display: flex;
  align-items: center;
  gap: 16px;
  flex: 1;

  & .btn {
    min-width: 20px;
    max-width: 20px;
    padding: 0;
    width: 20px;
    height: 20px;
    border: none;
  }
}

.d-and-d-btn {
  cursor: grab !important;
  opacity: 0.7;
  transition: all 0.2s ease;

  &:hover {
    opacity: 1;
    transform: scale(1.1);
  }

  &:active {
    transform: scale(0.95);
  }
}

.item-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
  margin-bottom: 10px;
}

.item-info h3 {
  margin: 0;
  color: #ffffff;
}

.item-link {
  font-size: 0.9em;
  color: #cccccc;
  font-style: italic;
}

.item-content {
  transition: all 0.3s ease;
  display: grid;
  grid-template-rows: 0fr;

  &.active {
    grid-template-rows: 1fr;
  }
}

.item-content-wrapper {
  display: flex;
  flex-direction: column;
  gap: 16px;
  overflow: hidden;
}

.item-content-inputs {
  display: flex;
  flex-direction: column;
  gap: 16px;

  & > label {
    display: flex;
    flex-direction: column;
    gap: 8px;

    &.file {
      max-width: 150px;
    }
  }
}

.item-content-footer {
  display: flex;
  gap: 16px;
}

.empty-state {
  text-align: center;
  padding: 40px;
  color: #cccccc;
}

.empty-state p {
  margin: 0;
  font-style: italic;
}

.toggle-wrapper {
  display: flex;
  align-items: center;
  gap: 16px;
}

.toggle-btn {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  min-width: 40px;
  height: 20px;
  border-radius: 25px;
  padding: 1px;
  transition: all 0.3s ease;

  &.active {
    background: linear-gradient(180deg, #10172d 0%, #0031bc 100%);

    & .toggle-btn-input {
      margin-right: 0;
      margin-left: auto;
    }
  }

  & .toggle-btn-input {
    margin-right: auto;
    margin-left: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #fff;
    transition: all 0.3s ease;
  }
}

.radio-pages-wrapper {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 12px;
}

.radio-page-item {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 6px;
  transition: background-color 0.2s ease;

  &:hover {
    background-color: rgba(255, 255, 255, 0.05);
  }
}

.radio-page-input {
  margin: 0;
  accent-color: #007bff;
}

.radio-page-text {
  color: #ffffff;
  font-size: 0.95em;
}
</style>
