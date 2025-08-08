<script setup lang="ts">
import { ref, computed } from 'vue';
import contactsStore from '../../stores/contactsStore';
import MyCheckboxInput from '../UI/MyCheckboxInput.vue';
import MyBtn from '../UI/MyBtn.vue';
import MyInput from '../UI/MyInput.vue';
import MyQuill from '../UI/MyQuill.vue';
import MyFileInput from '../UI/MyFileInput.vue';
import uppdateItemOnDB from '../../functions/uppdateItemOnDB';
import deleteItemOnDB from '../../functions/deleteItemOnDB';
import fetchWithCors from '../../utils/fetchWithCors';
import showSwal from '../../functions/showSwal';

const store = contactsStore();

const openContactTypes = ref<Record<string, boolean>>({});

const openContactItems = ref<Record<number, boolean>>({});

// Состояние для drag-and-drop
const draggedItem = ref<any>(null);
const dragOverItem = ref<any>(null);

// Computed для сортированных контактов
const sortedContacts = computed(() => {
  return store.contacts.slice().sort((a, b) => {
    // Сначала сортируем по типу, затем по sort_order
    if (a.type !== b.type) {
      return a.type.localeCompare(b.type);
    }
    return a.sort_order - b.sort_order;
  });
});

const handleFileChange = (file: File | null, contact: any) => {
  contact.icon_path = file;
};

const getImagePath = (iconPath: string | File | null): string => {
  if (!iconPath) return '';
  if (typeof iconPath === 'string') return iconPath;
  if (iconPath instanceof File) return URL.createObjectURL(iconPath);
  return '';
};

const toggleContactType = (type: string) => {
  // Если текущий тип уже открыт, закрываем его
  if (openContactTypes.value[type]) {
    openContactTypes.value[type] = false;
  } else {
    // Закрываем все типы
    Object.keys(openContactTypes.value).forEach((key) => {
      openContactTypes.value[key] = false;
    });
    // Открываем только выбранный тип
    openContactTypes.value[type] = true;
  }
};

const toggleContactItem = (contactId: number) => {
  // Если текущий контакт уже открыт, закрываем его
  if (openContactItems.value[contactId]) {
    openContactItems.value[contactId] = false;
  } else {
    // Закрываем все контакты
    Object.keys(openContactItems.value).forEach((key) => {
      openContactItems.value[Number(key)] = false;
    });
    // Открываем только выбранный контакт
    openContactItems.value[contactId] = true;
  }
};

const handleSaveContact = async (contact: any) => {
  await uppdateItemOnDB(contact, store.contactsApiUrl);
  await store.getContacts();
};

const handleDeleteContact = async (contact: any) => {
  await deleteItemOnDB(contact, store.contactsApiUrl);
  await store.getContacts();
};

// Функции для drag-and-drop
const handleDragStart = (event: DragEvent, contact: any) => {
  draggedItem.value = contact;
  if (event.dataTransfer) {
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', contact.contact_id.toString());
  }
};

const handleDragOver = (event: DragEvent, contact: any) => {
  event.preventDefault();
  if (event.dataTransfer) {
    event.dataTransfer.dropEffect = 'move';
  }
  dragOverItem.value = contact;
};

const handleDragLeave = () => {
  dragOverItem.value = null;
};

const handleDrop = async (event: DragEvent, targetContact: any) => {
  event.preventDefault();

  if (
    !draggedItem.value ||
    draggedItem.value.contact_id === targetContact.contact_id
  ) {
    draggedItem.value = null;
    dragOverItem.value = null;
    return;
  }

  // Проверяем, что контакты одного типа
  if (draggedItem.value.type !== targetContact.type) {
    draggedItem.value = null;
    dragOverItem.value = null;
    return;
  }

  await reorderContacts(draggedItem.value, targetContact);

  draggedItem.value = null;
  dragOverItem.value = null;
};

const reorderContacts = async (draggedContact: any, targetContact: any) => {
  try {
    // Получаем все контакты этого типа, отсортированные по sort_order
    const contactsOfType = sortedContacts.value.filter(
      (c) => c.type === draggedContact.type
    );

    // Находим индексы перетаскиваемого и целевого элементов
    const draggedIndex = contactsOfType.findIndex(
      (c) => c.contact_id === draggedContact.contact_id
    );
    const targetIndex = contactsOfType.findIndex(
      (c) => c.contact_id === targetContact.contact_id
    );

    if (draggedIndex === -1 || targetIndex === -1) return;

    // Создаем новый массив с измененным порядком
    const reorderedContacts = [...contactsOfType];
    const [removed] = reorderedContacts.splice(draggedIndex, 1);
    reorderedContacts.splice(targetIndex, 0, removed);

    // Обновляем sort_order для всех контактов этого типа
    const updateData = reorderedContacts.map((contact, index) => ({
      contact_id: contact.contact_id,
      sort_order: index + 1,
    }));

    // Отправляем обновление на сервер
    const response = await fetchWithCors(
      `${store.contactsApiUrl}?action=update-order`,
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
      store.updateContactsOrder(updateData);

      showSwal('Успешно', 'Порядок обновлен', 'success');
    } else {
      console.error('Ошибка обновления порядка:', response.error);
      showSwal('Ошибка', 'Не удалось обновить порядок', 'error');
    }
  } catch (error) {
    console.error('Ошибка при изменении порядка контактов:', error);
    showSwal('Ошибка', 'Произошла ошибка при изменении порядка', 'error');
  }
};
</script>

<template>
  <ul class="type-list list-style-none m-0 p-0">
    <li class="type-item" v-for="type in store.contactsTypes" :key="type">
      <div class="type-item-header">
        <h2 class="type-item-title m-0">Тип - {{ type }}</h2>
        <MyBtn variant="primary" @click="toggleContactType(type)">{{
          openContactTypes[type] ? 'Закрыть' : 'Открыть'
        }}</MyBtn>
      </div>
      <div
        class="type-item-content"
        :class="{ active: openContactTypes[type] }"
      >
        <ul class="contact-list list-style-none m-0 p-0">
          <li
            class="contact-item"
            v-for="contact in sortedContacts.filter(
              (contact) => contact.type === type
            )"
            :key="contact.contact_id"
            :class="{
              dragging: draggedItem?.contact_id === contact.contact_id,
              'drag-over': dragOverItem?.contact_id === contact.contact_id,
            }"
            @dragover="handleDragOver($event, contact)"
            @dragleave="handleDragLeave"
            @drop="handleDrop($event, contact)"
          >
            <div class="contact-item-header">
              <div
                class="contact-item-header-btn drag-btn"
                draggable="true"
                @dragstart="handleDragStart($event, contact)"
              >
                <img
                  style="display: block; margin-right: 10px"
                  src="./../../assets/d-and-d.svg"
                  alt=""
                  width="40"
                  height="40"
                />
              </div>
              <div class="contact-item-header-wrapper">
                <h2 class="title m-0">{{ contact.title }}</h2>
                <MyBtn
                  variant="primary"
                  @click="toggleContactItem(contact.contact_id)"
                  >{{
                    openContactItems[contact.contact_id]
                      ? 'Закрыть'
                      : 'Редактировать'
                  }}</MyBtn
                >
              </div>
            </div>
            <div
              class="contact-item-wrapper"
              :class="{ active: openContactItems[contact.contact_id] }"
            >
              <div class="contact-item-content">
                <div class="contact-input-wrapper">
                  <h3 class="subtitle m-0">Заголовок*</h3>
                  <MyInput variant="primary" v-model="contact.title" />
                </div>
                <div class="contact-input-wrapper quill-input">
                  <h3 class="subtitle m-0">Контент на странице*</h3>
                  <MyQuill v-model:content="contact.content" />
                </div>
                <div class="contact-input-wrapper">
                  <h3 class="subtitle m-0">Ссылка*</h3>
                  <MyInput variant="primary" v-model="contact.link" />
                </div>
                <div class="contact-input-wrapper">
                  <h3 class="subtitle m-0">Изображение*</h3>
                  <MyFileInput
                    :imgPath="getImagePath(contact.icon_path)"
                    :accept="'image/svg+xml'"
                    variant="primary"
                    @file-change="(file) => handleFileChange(file, contact)"
                  />
                </div>
                <div class="contact-input-wrapper">
                  <h3 class="subtitle m-0">Показывать на странице*</h3>
                  <div class="checkbox-wrapper">
                    <p class="m-0">Да</p>
                    <MyCheckboxInput
                      variant="primary"
                      v-model="contact.on_page"
                      :value="contact.on_page"
                      :checked="contact.on_page"
                    />
                    <p>Нет</p>
                  </div>
                </div>
                <div class="btn-wrapper">
                  <MyBtn variant="primary" @click="handleSaveContact(contact)"
                    >Сохранить</MyBtn
                  >
                  <MyBtn variant="primary" @click="handleDeleteContact(contact)"
                    >Удалить</MyBtn
                  >
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</template>

<style scoped>
.type-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.type-item-content {
  display: grid;
  grid-template-rows: 0fr;
  transition: all 0.3s ease;

  &.active {
    margin-top: 10px;
    grid-template-rows: 1fr;
  }
}

.type-item {
  padding: 20px;
  display: flex;
  flex-direction: column;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  width: 100%;
}

.type-item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.contact-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  overflow: hidden;
}

.contact-item {
  padding: 20px;
  display: flex;
  flex-direction: column;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  width: 100%;
}

.contact-item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.contact-item-wrapper {
  display: grid;
  grid-template-rows: 0fr;
  transition: all 0.3s ease;

  &.active {
    margin-top: 10px;
    grid-template-rows: 1fr;
  }
}

.contact-item-content {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.file-input-wrapper {
  width: 150px;
  height: 150px;
}

.checkbox-wrapper {
  display: flex;
  align-items: center;
  gap: 10px;
}

.contact-item-header-wrapper {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.quill-input {
  :deep(.ql-header) {
    display: none;
  }

  :deep(.ql-container) {
    color: #000;
    background-color: #fff;
    min-height: 120px;
    max-height: 120px;

    :deep(.ql-editor) {
      max-height: 100px;
    }
  }
}

.btn-wrapper {
  display: flex;
  gap: 10px;
  align-items: center;
}

/* Стили для drag-and-drop */
.contact-item {
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
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }
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
    pointer-events: none; /* Предотвращаем конфликты с drag событиями */

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
