<script setup lang="ts">
import { ref } from 'vue';
import contactsStore from '../../stores/contactsStore';
import MyCheckboxInput from '../UI/MyCheckboxInput.vue';
import MyBtn from '../UI/MyBtn.vue';
import MyInput from '../UI/MyInput.vue';
import MyQuill from '../UI/MyQuill.vue';
import MyFileInput from '../UI/MyFileInput.vue';
import uppdateItemOnDB from '../../functions/uppdateItemOnDB';
import deleteItemOnDB from '../../functions/deleteItemOnDB';

const store = contactsStore();

const openContactTypes = ref<Record<string, boolean>>({});

const openContactItems = ref<Record<number, boolean>>({});

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
</script>

<template>
  <ul class="type-list list-style-none m-0 p-0">
    <li class="type-item" v-for="type in store.contactsTypes" :key="type">
      <div class="type-item-header">
        <h2 class="type-item-title m-0">{{ type }}</h2>
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
            v-for="contact in store.contacts.filter(
              (contact) => contact.type === type
            )"
            :key="contact.contact_id"
          >
            <div class="contact-item-header">
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
</style>
