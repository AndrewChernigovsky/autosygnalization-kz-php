<template>
  <div class="services-admin">
    <h1>Редактор Услуг</h1>
    <div class="theme-dark">
      <Loader v-if="isLoading" />
      <div v-else class="services-list space-y-2">
        <div
          v-for="service in localServices.main"
          :key="service.id"
          class="service-item"
          :class="{
            'is-open': isServiceOpen(service.id),
            'new-service-highlight': service.id === highlightedServiceId,
          }"
          :data-service-id="service.id"
        >
          <div class="summary-header" @click="toggleService(service.id)">
            <span>{{ service.name }}</span>
            <span
              class="accordion-arrow"
              :class="{ 'is-open': isServiceOpen(service.id) }"
            ></span>
          </div>
          <Transition
            name="accordion"
            @before-enter="beforeEnter"
            @enter="enter"
            @after-enter="afterEnter"
            @before-leave="beforeLeave"
            @leave="leave"
          >
            <div v-if="isServiceOpen(service.id)" class="service-details">
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
                  :formats="formatsOptions"
                  contentType="html"
                  :content="service.description"
                  @update:content="(val: string) => (service.description = val)"
                />
              </div>
              <div class="form-group">
                <p>Рекомендуемый размер: 1000x1000px</p>
                <label>Изображение:</label>
                <ImageUpload
                  :path="getFullImagePath(service.image.src)"
                  @upload-success="
                    (data: { path: string; filename: string }) => handleImageUpload(data, service.id)
                  "
                  @image-cleared="service.image.src = ''"
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
                  :formats="formatsOptions"
                  contentType="html"
                  :content="service.services"
                  @update:content="(val: string) => (service.services = val)"
                />
              </div>
              <div class="form-group">
                <label>Цена:</label>
                <input type="number" v-model="service.cost" />
              </div>
              <div class="service-actions">
                <MyBtn
                  variant="secondary"
                  @click="saveService(service.id)"
                  class="btn-save"
                >
                  Сохранить
                </MyBtn>
                <MyBtn
                  variant="primary"
                  @click="deleteService(service.id)"
                  class="btn-delete"
                >
                  Удалить
                </MyBtn>
              </div>
            </div>
          </Transition>
        </div>
      </div>
      <MyBtn
        variant="primary"
        @click="addService"
        type="button"
        class="btn-save with-margin-bottom"
      >
        Добавить услугу
      </MyBtn>
    </div>
    <h1>Дополнительные услуги</h1>
    <div class="theme-dark">
      <Loader v-if="isLoading" />
      <div v-else class="services-list space-y-2">
        <div
          v-for="service in localServices.added"
          :key="service.id"
          class="service-item"
          :class="{ 'is-open': isServiceOpen(service.id) }"
          :data-service-id="service.id"
        >
          <div class="summary-header" @click="toggleService(service.id)">
            <span>{{ service.title }}</span>
            <span
              class="accordion-arrow"
              :class="{ 'is-open': isServiceOpen(service.id) }"
            ></span>
          </div>
          <Transition
            name="accordion"
            @before-enter="beforeEnter"
            @enter="enter"
            @after-enter="afterEnter"
            @before-leave="beforeLeave"
            @leave="leave"
          >
            <div v-if="isServiceOpen(service.id)" class="service-details">
              <div class="form-group">
                <label>Название:</label>
                <input type="text" v-model="service.title" />
              </div>
              <div class="form-group">
                <label>Цена:</label>
                <input type="number" v-model="service.price" />
              </div>
              <div class="service-actions">
                <MyBtn
                  variant="secondary"
                  @click="saveAddedService(service)"
                  class="btn-save"
                  >Сохранить</MyBtn
                >
                <MyBtn
                  variant="primary"
                  @click="deleteAddedService(service.id)"
                  class="btn-delete"
                >
                  Удалить
                </MyBtn>
              </div>
            </div>
          </Transition>
        </div>
      </div>
      <div class="service-actions">
        <MyBtn
          variant="primary"
          @click="addAddedService"
          type="button"
          class="btn-save with-margin-bottom"
        >
          Добавить дополнительную услугу
        </MyBtn>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import type { Service, AddedService } from './interfaces/Service';
import { API_URL } from '../../../config';
import Loader from '../../UI/Loader.vue';
import Swal from 'sweetalert2';
// @ts-ignore
import { QuillEditor } from '@rafaeljunioxavier/vue-quill-fix';
import '@rafaeljunioxavier/vue-quill-fix/dist/vue-quill.snow.css';
import ImageUpload from '../../UI/ImageUpload.vue';
import { nextTick } from 'vue';
import MyBtn from '../UI/MyBtn.vue';

const toolbarOptions = [
  [{ header: [1, 2, 3, false] }],
  ['bold', 'italic', 'underline', 'strike'],
  [{ list: 'ordered' }, { list: 'bullet' }],
  ['clean'],
];

const formatsOptions = [
  'header',
  'bold',
  'italic',
  'underline',
  'strike',
  'list',
];

const localServices = ref<{ main: Service[]; added: AddedService[] }>({
  main: [],
  added: [],
});
const isLoading = ref(true);
const highlightedServiceId = ref<string | null>(null);
const openServiceIds = ref<string[]>([]);

const toggleService = (id: string) => {
  const index = openServiceIds.value.indexOf(id);
  if (index === -1) {
    openServiceIds.value.push(id);
  } else {
    openServiceIds.value.splice(index, 1);
  }
};

const isServiceOpen = (id: string) => {
  return openServiceIds.value.includes(id);
};

const beforeEnter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = '0';
  htmlEl.style.opacity = '0';
};

const enter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = `${el.scrollHeight}px`;
  htmlEl.style.opacity = '1';
};

const afterEnter = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = 'auto';
};

const beforeLeave = (el: Element) => {
  const htmlEl = el as HTMLElement;
  htmlEl.style.height = `${el.scrollHeight}px`;
};

const leave = (el: Element) => {
  const htmlEl = el as HTMLElement;
  getComputedStyle(el).height;
  requestAnimationFrame(() => {
    htmlEl.style.height = '0';
    htmlEl.style.opacity = '0';
  });
};

function handleImageUpload(
  data: { path: string; filename: string },
  serviceId: string
) {
  if (data.path && localServices.value.main.find((s) => s.id === serviceId)) {
    const service = localServices.value.main.find((s) => s.id === serviceId);
    if (service) {
      service.image.src = data.path;
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
}

function deleteAddedService(serviceId: string) {
  const index = localServices.value.added.findIndex((s) => s.id === serviceId);
  if (index === -1) return;

  if (String(serviceId).startsWith('new-')) {
    localServices.value.added.splice(index, 1);
    return;
  }

  const service = localServices.value.added[index];
  Swal.fire({
    title: 'Вы уверены?',
    text: `Удалить дополнительную услугу "${service.title}"?`,
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
          '/server/php/admin/api/services/delete_added_service.php',
          {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: serviceId }),
          }
        );
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Ошибка удаления');
        }
        localServices.value.added.splice(index, 1);
        Swal.fire({
          title: 'Удалено!',
          text: 'Дополнительная услуга удалена.',
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

async function saveAddedService(service: AddedService) {
  const isNew = String(service.id).startsWith('new-');
  Swal.fire({
    title: 'Сохранение...',
    text: 'Пожалуйста, подождите',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
    background: '#333',
    color: '#fff',
  });
  try {
    const response = await fetch(
      '/server/php/admin/api/services/save_added_service.php',
      {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(service),
      }
    );
    if (!response.ok) {
      const errorText = await response.text();
      try {
        const errorData = JSON.parse(errorText);
        throw new Error(errorData.message || 'Ошибка сохранения');
      } catch (e) {
        throw new Error(`Не удалось сохранить услуги. ${errorText}`);
      }
    }
    const savedService = await response.json();
    const index = localServices.value.added.findIndex(
      (s) => s.id === service.id
    );
    if (index !== -1) {
      localServices.value.added[index] = savedService;
    }
    Swal.fire({
      title: 'Сохранено!',
      text: isNew
        ? 'Новая дополнительная услуга успешно добавлена.'
        : 'Дополнительная услуга успешно обновлена.',
      icon: 'success',
      background: '#333',
      color: '#fff',
    });
  } catch (error: any) {
    Swal.fire({
      title: 'Ошибка!',
      text: error.message,
      icon: 'error',
      background: '#333',
      color: '#fff',
    });
  }
}

function addAddedService() {
  const existingNewId = localServices.value.added.find((s) =>
    String(s.id).startsWith('new-')
  )?.id;

  if (existingNewId) {
    Swal.fire({
      title: 'Дополнительная услуга уже добавлена',
      text: 'Необходимо сначала сохранить уже добавленную дополнительную услугу.',
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
  const newService: AddedService = {
    id: newId,
    title: 'Новая дополнительная услуга',
    price: 0,
  };
  localServices.value.added.push(newService);
  highlightedServiceId.value = newId;

  setTimeout(() => {
    if (highlightedServiceId.value === newId) {
      highlightedServiceId.value = null;
    }
  }, 5000);

  Swal.fire({
    title: 'Добавлена форма для новой дополнительной услуги',
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

function addService() {
  const existingNewId = Object.keys(localServices.value.main).find((id) =>
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
  localServices.value.main.push(newService);
  highlightedServiceId.value = newId;
  openServiceIds.value.push(newId); // <-- Открываем аккордеон для нового элемента

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
      '/server/php/api/services/get_all_services.php'
    );
    const data = await response.json();
    localServices.value = {
      main: data.main || [],
      added: data.added || [],
    };
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
    const serviceToSave = localServices.value.main.find(
      (s) => s.id === serviceId
    );
    if (!serviceToSave) {
      throw new Error('Service not found');
    }

    let endpoint = `/server/php/admin/api/services/update_service.php`;
    let payload: any = { ...serviceToSave };

    if (isNew) {
      endpoint = `/server/php/admin/api/services/create_service.php`;
    } else {
      // For existing services, we don't need to send old_image_path as it's not in the Service interface
      // payload.old_image_path = originalService.image.src;
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
      // If it was a new service, update the localServices.main array
      const index = localServices.value.main.findIndex(
        (s) => s.id === serviceId
      );
      if (index !== -1) {
        localServices.value.main[index] = savedService;
      }
    } else {
      // If it was an existing service, update the localServices.main array
      const index = localServices.value.main.findIndex(
        (s) => s.id === serviceId
      );
      if (index !== -1) {
        localServices.value.main[index] = JSON.parse(
          JSON.stringify(serviceToSave)
        );
      }
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
  // Если это новая, еще не сохраненная услуга, удаляем ее локально
  if (String(serviceId).startsWith('new-')) {
    const index = localServices.value.main.findIndex((s) => s.id === serviceId);
    if (index !== -1) {
      localServices.value.main.splice(index, 1);
    }
    return;
  }

  const serviceToDelete = localServices.value.main.find(
    (s) => s.id === serviceId
  );
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

        const index = localServices.value.main.findIndex(
          (s) => s.id === serviceId
        );
        if (index !== -1) {
          localServices.value.main.splice(index, 1);
        }

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
    background-color: black;
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
  background-color: inherit;
}

.summary-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  font-weight: bold;
  cursor: pointer;
  background-color: inherit;
  color: #eee;
  border-radius: 5px;
  transition: border-radius 0.15s ease-in-out;
}

.accordion-arrow {
  width: 10px;
  height: 10px;
  border-right: 2px solid #ccc;
  border-bottom: 2px solid #ccc;
  transform: rotate(45deg);
  transition: transform 0.3s ease-out;
}

.accordion-arrow.is-open {
  transform: translateY(2px) rotate(-135deg);
}

.service-item.is-open > .summary-header {
  border-bottom: 1px solid #444;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}

.service-details {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
  overflow: hidden;
  transition: height 0.3s ease-out, opacity 0.3s ease-out;
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
  background-color: inherit;
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
  border-top: 1px solid #444;
  padding-top: 20px;
  margin-top: 10px;
}

/* Add styles for Quill editor if needed */
.form-group :deep(.ql-editor) {
  background-color: white;
  color: black;
  min-height: 150px;
}

.form-group :deep(.ql-toolbar) {
  background-color: black;
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
