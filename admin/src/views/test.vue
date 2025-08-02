<template>
  <div style="max-width: 400px; margin: 40px auto; padding: 24px; border: 1px solid #eee; border-radius: 8px;">
    <h2>Тест загрузки иконки контакта</h2>
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <div style="margin-bottom: 12px;">
        <label>Тип контакта:</label>
        <input v-model="form.type" name="type" required placeholder="main-phone" />
      </div>
      <div style="margin-bottom: 12px;">
        <label>Название:</label>
        <input v-model="form.title" name="title" required placeholder="Основной" />
      </div>
      <div style="margin-bottom: 12px;">
        <label>Контент:</label>
        <input v-model="form.content" name="content" placeholder="+7 777 777 77 77" />
      </div>
      <div style="margin-bottom: 12px;">
        <label>Ссылка:</label>
        <input v-model="form.link" name="link" placeholder="tel:+77777777777" />
      </div>
      <div style="margin-bottom: 12px;">
        <label>Иконка:</label>
        <input ref="iconFile" type="file" @change="onFileChange" name="icon_path" accept="image/*"/>
      </div>
      <button type="submit">Загрузить</button>
    </form>
    <!-- <div v-if="result" style="margin-top: 20px;">
      <div v-if="result.success" style="color: green;">
        <b>Успех!</b> Контакт создан.<br>
        ID: {{ result.contact_id }}<br>
        <span v-if="result.icon_path">Путь к иконке: {{ result.icon_path }}</span>
      </div>
      <div v-else style="color: red;">
        <b>Ошибка:</b> {{ result.error }}
      </div>
    </div> -->
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
const form = ref({
    type: '',
    title: '',
    content: '',
    link: '',
    icon_path: null,
  });
  const iconFile = ref<File | null>(null);

  function onFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
      iconFile.value = target.files[0];
    }
  }


  async function submitForm() {
    const formData = new FormData();
    formData.append('type', form.value.type);
    formData.append('title', form.value.title);
    formData.append('content', form.value.content);
    formData.append('link', form.value.link);
    formData.append('icon_path', iconFile.value as File);

    const response = await fetch('/server/php/admin/api/contacts/contact.php', { 
        method: 'POST',
        body: formData,
    });
    const data = await response.json();
    console.log(data);
  }
</script>
