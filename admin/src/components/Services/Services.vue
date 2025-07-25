<template>
  <div class="services-admin">
    <h1>Редактор Услуг</h1>
    <div class="theme-dark">
      <Loader v-if="isLoading" />
      <div v-else class="services-list space-y-2">
        <details
          v-for="service in localServices"
          :key="service.id"
          class="service-item"
          :class="{
            'new-service-highlight': service.id === highlightedServiceId,
          }"
          :data-service-id="service.id"
        >
          {{ console.log(service, 'service') }}
          <summary>{{ service.name }}</summary>
          <div class="service-details">
            <div class="form-group">
              <label>Название:</label>
              <input type="text" v-model="service.name" />
            </div>
            <div class="form-group">
              <label>Описание:</label>
              <QuillEditor
                :key="service.id + '-desc'"
                theme="snow"
                :toolbar="toolbarOptions"
                contentType="html"
                v-model:content="service.description"
              />
            </div>
            <div class="form-group">
              <p>Рекомендуемый размер: 1000x1000px</p>
              <label>Изображение:</label>
              <ImageUpload
                :path="getFullImagePath(service.image.src)"
                @upload-success="(data) => handleImageUpload(data, service.id)"
                @image-cleared="localServices[service.id].image.src = ''"
                :extraData="{ serviceId: service.id }"
                serviceImage
              />
            </div>
            <div class="form-group">
              <label>Список услуг:</label>
              <QuillEditor
                :key="service.id + '-serv'"
                theme="snow"
                :toolbar="toolbarOptions"
                contentType="html"
                v-model:content="service.services"
              />
            </div>
            <div class="form-group">
              <label>Цена:</label>
              <input type="number" v-model="service.cost" />
            </div>
            <div class="service-actions">
              <button @click="saveService(service.id)" class="btn-save">
                Сохранить
              </button>
              <button @click="deleteService(service.id)" class="btn-delete">
                Удалить
              </button>
            </div>
          </div>
        </details>
      </div>
      <button
        @click="addService"
        type="button"
        class="btn-save with-margin-bottom"
      >
        Добавить услугу
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import type { Service } from './interfaces/Service';
import { API_URL } from '../../../config';
import Loader from '../../UI/Loader.vue';
import Swal from 'sweetalert2';
// @ts-ignore
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import ImageUpload from '../../UI/ImageUpload.vue';
import { nextTick } from 'vue';

const toolbarOptions = [
  [{ header: [1, 2, 3, false] }],
  ['bold', 'italic', 'underline', 'strike'],
  [{ list: 'ordered' }, { list: 'bullet' }],
  ['clean'],
];

const services = ref<Record<string, Service>>({});
const localServices = ref<Record<string, Service>>({});
const isLoading = ref(true);
const highlightedServiceId = ref<string | null>(null);

function handleImageUpload(
  data: { path: string; filename: string },
  serviceId: string
) {
  if (data.path && localServices.value[serviceId]) {
    localServices.value[serviceId].image.src = data.path;
    Swal.fire({
      title: 'Успешно!',
      text: `Изображение ${data.filename} загружено.`,
      icon: 'success',
      background: '#333',
      color: '#fff',
      timer: 2000,
      showConfirmButton: false,
    });
  }
}

function addService() {
  const existingNewId = Object.keys(localServices.value).find((id) =>
    id.startsWith('new-')
  );

  if (existingNewId) {
    Swal.fire({
      title: 'Услуга уже добавлена',
      text: 'Необходимо сначала сохранить уже добавленную услугу.',
      icon: 'warning',
      background: '#333',
      color: '#fff',
    });

    highlightedServiceId.value = null;
    nextTick(() => {
      highlightedServiceId.value = existingNewId;
      setTimeout(() => {
        if (highlightedServiceId.value === existingNewId) {
          highlightedServiceId.value = null;
        }
      }, 5000);

      const element = document.querySelector(
        `[data-service-id="${existingNewId}"]`
      );
      if (element) {
        const detailsElement = element as HTMLDetailsElement;
        detailsElement.open = true;
        detailsElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });

    return;
  }

  const newId = `new-${Date.now()}`;
  const newService: Service = {
    id: newId,
    name: 'Новая услуга',
    description: '',
    image: { src: '', description: '' },
    href: '',
    services: '',
    cost: 0,
    currency: 'KZT',
  };
  localServices.value[newId] = newService;
  highlightedServiceId.value = newId;

  setTimeout(() => {
    if (highlightedServiceId.value === newId) {
      highlightedServiceId.value = null;
    }
  }, 5000);

  Swal.fire({
    title: 'Добавлена форма для новой услуги',
    text: 'Заполните данные и нажмите "Сохранить".',
    icon: 'info',
    background: '#333',
    color: '#fff',
    timer: 3000,
    showConfirmButton: false,
  });

  nextTick(() => {
    const element = document.querySelector(`[data-service-id="${newId}"]`);
    if (element) {
      const detailsElement = element as HTMLDetailsElement;
      detailsElement.open = true;
      detailsElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
}

async function fetchServices() {
  try {
    const response = await fetch(
      `/server/php/api/services/get_all_services.php`
    );
    if (!response.ok) throw new Error('Failed to fetch services');
    const data = await response.json();

    const servicesWithId = Object.entries(data).reduce((acc, [id, service]) => {
      acc[id] = { ...(service as Service), id };
      return acc;
    }, {} as Record<string, Service>);

    services.value = servicesWithId;
    localServices.value = JSON.parse(JSON.stringify(servicesWithId));
  } catch (error) {
    console.error(error);
  } finally {
    isLoading.value = false;
  }
}

async function saveService(serviceId: string) {
  const isNew = serviceId.toString().startsWith('new-');
  Swal.fire({
    title: isNew ? 'Добавление услуги...' : 'Сохранение...',
    text: 'Пожалуйста, подождите',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
    background: '#333',
    color: '#fff',
  });

  try {
    const serviceToSave = localServices.value[serviceId];
    let endpoint = `/server/php/admin/api/services/update_service.php`;
    let payload: any = { ...serviceToSave };

    if (isNew) {
      endpoint = `/server/php/admin/api/services/create_service.php`;
    } else {
      const originalService = services.value[serviceId];
      payload.old_image_path = originalService.image.src;
    }

    const response = await fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'Failed to save service');
    }

    const savedService = await response.json();

    if (isNew) {
      delete localServices.value[serviceId];
      localServices.value[savedService.id] = savedService;
      services.value[savedService.id] = savedService;
    } else {
      services.value[serviceId] = JSON.parse(JSON.stringify(serviceToSave));
    }

    Swal.fire({
      title: isNew ? 'Добавлено!' : 'Сохранено!',
      text: isNew
        ? 'Новая услуга была успешно добавлена.'
        : 'Услуга была успешно обновлена.',
      icon: 'success',
      background: '#333',
      color: '#fff',
    });
  } catch (error: any) {
    console.error(error);
    Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось сохранить услугу. ' + error.message,
      icon: 'error',
      background: '#333',
      color: '#fff',
    });
  }
}

async function deleteService(serviceId: string) {
  const serviceToDelete = localServices.value[serviceId];
  if (!serviceToDelete) return;

  Swal.fire({
    title: 'Вы уверены?',
    text: `Вы действительно хотите удалить услугу "${serviceToDelete.name}"? Это действие необратимо.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Да, удалить!',
    cancelButtonText: 'Отмена',
    background: '#333',
    color: '#fff',
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const response = await fetch(
          `/server/php/admin/api/services/delete_service.php`,
          {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              id: serviceId,
              image_path: serviceToDelete.image.src,
            }),
          }
        );

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Failed to delete service');
        }

        delete localServices.value[serviceId];
        delete services.value[serviceId];

        Swal.fire({
          title: 'Удалено!',
          text: 'Услуга была успешно удалена.',
          icon: 'success',
          background: '#333',
          color: '#fff',
        });
      } catch (error: any) {
        Swal.fire({
          title: 'Ошибка!',
          text: 'Не удалось удалить услугу. ' + error.message,
          icon: 'error',
          background: '#333',
          color: '#fff',
        });
      }
    }
  });
}

function getFullImagePath(path: string): string {
  if (!path) return '';
  if (path.startsWith('blob:') || path.startsWith('http')) {
    return path;
  }
  return `${API_URL}${path}`;
}

onMounted(fetchServices);
</script>

<style scoped>
@keyframes highlight-fade-item {
  from {
    background-color: #2f4835;
  }
  to {
    background-color: #333;
  }
}

@keyframes highlight-fade-summary {
  from {
    background-color: #35523d;
  }
  to {
    background-color: #3a3a3a;
  }
}

.service-item.new-service-highlight {
  animation: highlight-fade-item 5s ease-out forwards;
}

.service-item.new-service-highlight > summary {
  animation: highlight-fade-summary 5s ease-out forwards;
}

.services-admin h1 {
  text-align: center;
  color: #f1f1f1;
}

.theme-dark {
  background-color: #333;
  color: #f1f1f1;
  min-height: 100vh;
  padding: 20px;
  max-width: 1440px;
  border-radius: 10px;
  margin: 0 auto;
}

.with-margin-bottom {
  margin-top: 20px;
}

.space-y-2 {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.service-item {
  border: 1px solid #444;
  border-radius: 5px;
  background-color: #333;
}

.service-item > summary {
  padding: 15px;
  font-weight: bold;
  cursor: pointer;
  background-color: #3a3a3a;
  color: #eee;
  border-radius: 5px;
  list-style: none;
}

.service-item > summary::-webkit-details-marker {
  display: none;
}

.service-item[open] > summary {
  border-bottom: 1px solid #444;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

.service-details {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  margin-bottom: 5px;
  font-weight: bold;
  color: #ccc;
}

.form-group input,
.form-group textarea {
  padding: 10px;
  background-color: #444;
  border: 1px solid #555;
  color: #fff;
  border-radius: 4px;
}

.image-uploader {
  position: relative;
  width: 150px;
  height: 150px;
}

.image-container {
  width: 100%;
  height: 100%;
  border: 1px dashed #666;
  border-radius: 4px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.loader-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
}

.service-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  border-top: 1px solid #444;
  padding-top: 20px;
  margin-top: 10px;
}

.btn-save,
.btn-delete {
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  color: white;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-save {
  background-color: #28a745;
}

.btn-delete {
  background-color: #ff4d4f;
}

/* Add styles for Quill editor if needed */
.form-group :deep(.ql-editor) {
  background-color: #444;
  color: #fff;
  min-height: 150px;
}

.form-group :deep(.ql-toolbar) {
  background-color: #555;
  border-color: #666;
}

.form-group :deep(.ql-toolbar .ql-picker-label) {
  color: #fff;
}

.form-group :deep(.ql-toolbar .ql-stroke) {
  stroke: #ccc;
}
.form-group :deep(.ql-toolbar .ql-fill) {
  fill: #ccc;
}
.form-group :deep(.ql-toolbar button:hover .ql-stroke),
.form-group :deep(.ql-toolbar .ql-picker-label:hover .ql-stroke) {
  stroke: #fff;
}
.form-group :deep(.ql-toolbar button:hover .ql-fill),
.form-group :deep(.ql-toolbar .ql-picker-label:hover .ql-fill) {
  fill: #fff;
}
.form-group :deep(.ql-toolbar button.ql-active .ql-stroke),
.form-group :deep(.ql-toolbar .ql-picker-label.ql-active .ql-stroke) {
  stroke: #007bff;
}
.form-group :deep(.ql-toolbar button.ql-active .ql-fill),
.form-group :deep(.ql-toolbar .ql-picker-label.ql-active .ql-fill) {
  fill: #007bff;
}
</style>
