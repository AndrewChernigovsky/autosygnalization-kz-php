<script setup lang="ts">
import { ref } from 'vue';
import contactsStore from '../../stores/contactsStore';
import MyCheckboxInput from '../UI/MyCheckboxInput.vue';
import MyInput from '../UI/MyInput.vue';
import MyQuill from '../UI/MyQuill.vue';
import MyFileInput from '../UI/MyFileInput.vue';
import MyBtn from '../UI/MyBtn.vue';

const store = contactsStore();

// Объект для отслеживания состояния каждого типа контакта
const openContactTypes = ref<Record<string, boolean>>({});

// Функция для переключения состояния конкретного типа (аккордеон-эффект)
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
</script>

<template>
  <ul class="type-list">
    <li class="type-item" v-for="type in store.contactsTypes" :key="type">
      <div class="type-item-header">
      <h2 class="type-item-title">{{ type }}</h2>
      <MyBtn variant="primary" @click="toggleContactType(type)">{{
        openContactTypes[type] ? 'Закрыть' : 'Открыть'
      }}</MyBtn>
      </div>
      <div
        class="type-item-content"
        :class="{ active: openContactTypes[type] }"
      >
        <ul class="contact-list">
          <li
            class="contact-item"
            v-for="contact in store.contacts.filter(
              (contact) => contact.type === type
            )"
            :key="contact.contact_id"
          >
            <p>{{ contact.title }}</p>
            <p>{{ contact.content }}</p>
            <MyCheckboxInput
              variant="primary"
              v-model="contact.on_page"
              :name="contact.contact_id"
              :value="contact.on_page"
              :checked="contact.on_page"
            />
          </li>
        </ul>
      </div>
    </li>
  </ul>
</template>

<style scoped>
.type-item-content {
  display: grid;
  grid-template-rows: 0fr;
  transition: all 0.3s ease;

  &.active {
    grid-template-rows: 1fr;
  }
}

.contact-list {
  overflow: hidden;
}
</style>
