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
import DraggableList from '../UI/DraggableList.vue';

const store = contactsStore();

const openContactTypes = ref<Record<string, boolean>>({});

const openContactItems = ref<Record<number, boolean>>({});

// Хранилище для исходных значений контактов
const originalContacts = ref<Record<number, any>>({});

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

    // Сохраняем исходные значения контакта при открытии
    const contact = store.contacts.find((c) => c.contact_id === contactId);
    if (contact) {
      originalContacts.value[contactId] = {
        title: contact.title,
        content: contact.content,
        link: contact.link,
        icon_path: contact.icon_path,
        on_page: contact.on_page,
      };
    }
  }
};

const handleSaveContact = async (contact: any) => {
  if (!contact.title.trim()) {
    showSwal('Ошибка', 'Заголовок не может быть пустым', 'error');
    return;
  }

  // Проверяем, есть ли изменения
  const original = originalContacts.value[contact.contact_id];
  if (original) {
    const hasChanges =
      original.title !== contact.title ||
      original.content !== contact.content ||
      original.link !== contact.link ||
      original.icon_path !== contact.icon_path ||
      original.on_page !== contact.on_page;

    if (!hasChanges) {
      showSwal('Предупреждение', 'Нет изменений в контактах.', 'warning');
      return;
    }
  }

  await uppdateItemOnDB(contact, store.contactsApiUrl);
  await store.getContacts();
};

const handleDeleteContact = async (contact: any) => {
  await deleteItemOnDB(contact, store.contactsApiUrl);
  await store.getContacts();
};

const handleReorder = async (reorderedContactsForType: any[]) => {
  try {
    const updateData = reorderedContactsForType.map((contact, index) => ({
      contact_id: contact.contact_id,
      sort_order: index + 1,
    }));

    // Optimistic update
    store.updateContactsOrder(updateData);

    // Send to server
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
      showSwal('Успешно', 'Порядок обновлен', 'success');
    } else {
      console.error('Ошибка обновления порядка:', response.error);
      showSwal('Ошибка', 'Не удалось обновить порядок', 'error');
      await store.getContacts(); // Revert on error
    }
  } catch (error) {
    console.error('Ошибка при изменении порядка контактов:', error);
    showSwal('Ошибка', 'Произошла ошибка при изменении порядка', 'error');
    await store.getContacts(); // Revert on error
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
        <DraggableList
          :model-value="
            sortedContacts.filter((contact) => contact.type === type)
          "
          item-key="contact_id"
          tag="ul"
          class="contact-list list-style-none m-0 p-0"
          @reorder="handleReorder"
        >
          <template #item="{ item, dragHandleProps, isDragOver }">
            <li class="contact-item" :class="{ 'drag-over': isDragOver }">
              <div class="contact-item-header">
                <div
                  class="contact-item-header-btn drag-btn"
                  v-bind="dragHandleProps"
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
                  <h2 class="title m-0">
                    {{ item.title ? item.title : 'Заголовок' }}
                  </h2>
                  <MyBtn
                    variant="secondary"
                    @click="toggleContactItem(item.contact_id)"
                    >{{
                      openContactItems[item.contact_id]
                        ? 'Закрыть'
                        : 'Редактировать'
                    }}</MyBtn
                  >
                </div>
              </div>
              <div
                class="contact-item-wrapper"
                :class="{ active: openContactItems[item.contact_id] }"
              >
                <div class="contact-item-content">
                  <div class="contact-input-wrapper">
                    <h3 class="subtitle m-0">Заголовок*</h3>
                    <MyInput variant="primary" v-model="item.title" />
                    <p class="addcontact-help m-0">
                      *Введите заголовок, он <strong>НЕ</strong> будет
                      отображаться на странице
                    </p>
                  </div>
                  <div class="contact-input-wrapper quill-input">
                    <h3 class="subtitle m-0">Контент на странице*</h3>
                    <MyQuill v-model:content="item.content" />
                    <p class="addcontact-help m-0">
                      *Введите текст, который будет отображаться на странице
                      <br />
                      **Если контакт google карта то оставьте поле пустым
                    </p>
                  </div>
                  <div class="contact-input-wrapper" v-if="item.type !== 'Как к нам добраться'">
                    <h3 class="subtitle m-0">Ссылка*</h3>
                    <MyInput variant="primary" v-model="item.link" />
                    <p class="addcontact-help m-0">
                      *Если нет ссылки, оставьте поле пустым <br />
                      **Если контакт телефон, то ссылка должна быть в формате
                      +79999999999, без пробелов <br />
                      ***Если контакт email, то ссылка должна быть в формате
                      example@example.com
                    </p>
                  </div>
                  <div
                    class="contact-input-wrapper"
                    v-if="
                      item.type === 'Социальные сети' ||
                      item.type === 'Мессенджер'
                    "
                  >
                    <h3 class="subtitle m-0">Изображение*</h3>
                    <MyFileInput
                      :imgPath="getImagePath(item.icon_path)"
                      :accept="'image/svg+xml'"
                      variant="primary"
                      @file-change="(file) => handleFileChange(file, item)"
                    />
                    <p class="addcontact-help m-0">
                      *Изображения должны быть в формате SVG, не более 50кб.
                    </p>
                  </div>
                  <div class="contact-input-wrapper">
                    <h3 class="subtitle m-0">Показывать на странице*</h3>
                    <div class="checkbox-wrapper">
                      <p class="m-0">Да</p>
                      <MyCheckboxInput
                        variant="primary"
                        v-model="item.on_page"
                        :value="item.on_page"
                        :checked="item.on_page"
                      />
                      <p>Нет</p>
                    </div>
                  </div>
                  <div class="btn-wrapper">
                    <MyBtn variant="therdary" @click="handleSaveContact(item)"
                      >Сохранить</MyBtn
                    >
                    <MyBtn variant="primary" @click="handleDeleteContact(item)"
                      >Удалить</MyBtn
                    >
                  </div>
                </div>
              </div>
            </li>
          </template>
        </DraggableList>
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

.addcontact-help {
  font-size: 12px;
}

/* Стили для drag-and-drop */
.contact-item {
  transition: all 0.2s ease;
}
.contact-item.drag-over {
  border-color: #007bff;
  background-color: rgba(0, 123, 255, 0.1);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
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
