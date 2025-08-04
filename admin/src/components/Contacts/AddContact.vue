<script setup lang="ts">
import { ref } from 'vue';
import useContactsStore from '../../stores/contactsStore';
import MyBtn from '../UI/MyBtn.vue';
import MyInput from '../UI/MyInput.vue';
import MyFileInput from '../UI/MyFileInput.vue';
import MyCheckboxInput from '../UI/MyCheckboxInput.vue';
import MyRadioInput from '../UI/MyRadioInput.vue';
import MyQuill from '../UI/MyQuill.vue';

interface INewContact {
  type: string;
  title: string;
  content: string;
  icon_path?: File | null;
  link: string;
  sort_order: number | null;
  on_page: boolean;
}

const addNewContactActive = ref(true);

const newContact = ref<INewContact>({
  type: '',
  title: '',
  content: '',
  icon_path: null,
  link: '',
  sort_order: null,
  on_page: false,
});

const contactsStore = useContactsStore();

const contactTypes = contactsStore.contactsTypes;

const handleFileChange = (file: File | null) => {
  newContact.value.icon_path = file;
};

const addNewContact = () => {
  // contactsStore.addContact(
  //   contactsStore.contactsUrl,
  //   newContact.value,
  // );
  console.log(newContact.value);
};
</script>

<template>
  <div class="addcontact">
    <div class="addcontact-header">
      <h2 class="titile m-0">Добавить новый контакт</h2>
      <MyBtn
        variant="primary"
        @click="addNewContactActive = !addNewContactActive"
        >{{ addNewContactActive ? 'Закрыть' : 'Добавить' }}</MyBtn
      >
    </div>
    <div
      class="addcontact-body-wrapper"
      :class="{ active: addNewContactActive }"
    >
      <div class="addcontact-body-content">
        <div class="addcontact-type-wrapper">
          <div class="addcontact-type-title">
            <h3 class="subtitle m-0">Выберите тип контакта*</h3>
          </div>
          <ul class="addcontact-type-list list-style-none m-0 p-0">
            <li
              v-for="contactType in contactTypes"
              :key="contactType"
              class="addcontact-type-item"
            >
              <label
                class="addcontact-type-label"
                :class="{ active: newContact.type === contactType }"
              >
                <h3 class="subtitle m-0">{{ contactType }}</h3>
                <MyRadioInput
                  variant="primary"
                  name="contact"
                  v-model="newContact.type"
                  :value="contactType"
                  :checked="newContact.type === contactType"
                />
              </label>
            </li>
          </ul>
        </div>
        <div class="addcontact-inputs-wrapper">
          <div class="addcontact-label">
            <h3 class="subtitle m-0">Заголовок*</h3>
            <MyInput variant="primary" v-model="newContact.title" />
            <p class="addcontact-help m-0">
              *Введите заголовок, он <strong>НЕ</strong> будет отображаться на
              странице
            </p>
          </div>
          <div class="addcontact-label quill-input">
            <h3 class="subtitle m-0">Контент на странице*</h3>
            <MyQuill v-model:content="newContact.content" />
            <p class="addcontact-help m-0">
              *Введите текст, который будет отображаться на странице
            </p>
          </div>
          <div class="addcontact-label">
            <h3 class="subtitle m-0">Ссылка</h3>
            <MyInput variant="primary" v-model="newContact.link" />
            <p class="addcontact-help m-0">
              *Если нет ссылки, оставьте поле пустым <br />
              **Если контакт телефон, то ссылка должна быть в формате
              +79999999999, без пробелов <br />
              ***Если контакт email, то ссылка должна быть в формате
              example@example.com
            </p>
          </div>
          <div class="addcontact-label file-input">
            <h3 class="subtitle m-0">Изображение</h3>
            <div class="file-input-wrapper">
              <MyFileInput
                :accept="'image/svg+xml'"
                @file-change="handleFileChange"
              />
            </div>
            <p class="addcontact-help m-0">
              *Изображения должны быть в формате SVG, не более 50кб.
            </p>
          </div>
        </div>
        <div class="addcontact-checkbox-wrapper">
          <div class="addcontact-checkbox-title">
            <h3>Показывать на странице</h3>
          </div>
          <label>
            <span>Да</span>
            <MyCheckboxInput
              variant="primary"
              v-model="newContact.on_page"
              :value="true"
            />
            <span>Нет</span>
          </label>
        </div>
        <div class="addcontact-buttons-wrapper">
          <MyBtn variant="primary" @click="addNewContact">Добавить</MyBtn>
          <MyBtn variant="secondary" @click="addNewContactActive = false"
            >Отмена</MyBtn
          >
          <MyBtn variant="secondary" @click="addNewContactActive = false"
            >Сбросить</MyBtn
          >
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.addcontact {
  display: flex;
  flex-direction: column;
  padding: 20px;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  width: 100%;
}

.addcontact-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  overflow: hidden;
}

.addcontact-body-wrapper {
  display: grid;
  grid-template-rows: 0fr;
  transition: all 0.3s ease;

  &.active {
    margin-top: 20px;
    grid-template-rows: 1fr;
  }
}

.addcontact-body-content {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.addcontact-type-title {
  margin-bottom: 10px;
}

.addcontact-type-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  padding: 10px;
}

.addcontact-type-label {
  display: block;
  position: relative;
  padding: 20px;
  color: #fff;
  transition: all 0.3s ease;

  &:hover {
    opacity: 0.7;
  }

  &:active {
    opacity: 0.3;
  }

  & .subtitle {
    position: relative;
    z-index: 2;
    font-size: 16px;
  }

  &.active {
    opacity: 1;
    .subtitle {
      color: #000;
    }
  }
}

.addcontact-inputs-wrapper {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.addcontact-label {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.file-input-wrapper {
  width: 150px;
  height: 150px;
}

.addcontact-help {
  font-size: 12px;
}

.addcontact-checkbox-wrapper {
  & label {
    display: flex;
    gap: 10px;
  }
}

.addcontact-buttons-wrapper {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

.quill-input {
  :deep(.ql-header) {
    display: none;
  }

  :deep(.ql-container) {
    max-height: 120px;

    :deep(.ql-editor) {
      max-height: 100px;
    }
  }
}
</style>
