<template>
  <div class="services-admin">
    <h1 class="my-title">Услуги</h1>
    <div class="services-admin-wrapper">
      <div class="services-admin-container">
        <div class="services-admin-header">
          <label for="">Основные услуги</label>
          <MyBtn variant="primary" @click="toggleAccordion('main')">
            {{ openAccordionIds.main ? 'Свернуть' : 'Развернуть' }}
          </MyBtn>
        </div>
        <MyTransition>
          <div class="theme-dark" v-if="openAccordionIds.main">
            <div v-if="!isLoading" class="services-list space-y-2">
              <div
                v-for="service in localServices.main"
                :key="service.id"
                class="service-item"
                :class="{
                  'is-open': isMainServiceOpen(service.id),
                  'new-service-highlight': service.id === highlightedServiceId,
                }"
                :data-service-id="service.id"
              >
                <div class="summary-header">
                  <span>{{ service.name }}</span>
                  <MyBtn
                    variant="primary"
                    class="accordion-arrow"
                    :class="{ 'is-open': isMainServiceOpen(service.id) }"
                    @click="toggleMainService(service.id)"
                  >
                    {{
                      isMainServiceOpen(service.id)
                        ? 'Свернуть'
                        : 'Редактировать'
                    }}</MyBtn
                  >
                </div>
                <MyTransition>
                  <div
                    v-if="isMainServiceOpen(service.id)"
                    class="service-details"
                  >
                    <div class="form-group">
                      <label>Название:</label>
                      <input type="text" v-model="service.name" />
                    </div>
                    <div class="form-group">
                      <label>Описание:</label>
                      <MyQuill
                        :key="service.id + '-desc'"
                        :content="service.description"
                        @update:content="(val: string) => (service.description = val)"
                      />
                    </div>
                    <div class="form-group">
                      <p>Рекомендуемый размер: 1000x1000px</p>
                      <label>Изображение:</label>
                      <ImageUpload :path="getFullImagePath(service.image.src)" @upload-success="handleImageUpload"
                        @image-cleared="service.image.src = ''" :data="{ imageID: service.id }" serviceImage />
                    </div>
                    <div class="form-group">
                      <label>Список услуг:</label>
                      <MyQuill
                        :key="service.id + '-serv'"
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
                        Сохранить услугу
                      </MyBtn>
                      <MyBtn
                        variant="primary"
                        @click="deleteService(service.id)"
                        class="btn-delete"
                      >
                        Удалить услугу
                      </MyBtn>
                    </div>
                  </div>
                </MyTransition>
              </div>
            </div>
            <MyBtn
              variant="secondary"
              @click="addService"
              type="button"
              class="btn-save btn-add-service"
              :disabled="isAddingMainService"
            >
              Добавить услугу
            </MyBtn>
          </div>
        </MyTransition>
      </div>
      <div class="services-admin-container">
        <div class="services-admin-header">
          <label for="">Дополнительные услуги</label>
          <MyBtn variant="primary" @click="toggleAccordion('added')">
            {{ openAccordionIds.added ? 'Свернуть' : 'Развернуть' }}
          </MyBtn>
        </div>
        <MyTransition>
          <div class="theme-dark" v-if="openAccordionIds.added">
            <div v-if="!isLoading" class="services-list space-y-2">
              <div
                v-for="service in localServices.added"
                :key="service.id"
                class="service-item"
                :class="{ 'is-open': isAddedServiceOpen(service.id) }"
                :data-service-id="service.id"
              >
                <div class="summary-header">
                  <span>{{ service.title }}</span>
                  <MyBtn
                    variant="primary"
                    class="accordion-arrow"
                    :class="{ 'is-open': isAddedServiceOpen(service.id) }"
                    @click="toggleAddedService(service.id)"
                    >{{
                      isAddedServiceOpen(service.id)
                        ? 'Свернуть'
                        : 'Редактировать'
                    }}</MyBtn
                  >
                </div>
                <MyTransition>
                  <div
                    v-if="isAddedServiceOpen(service.id)"
                    class="service-details"
                  >
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
                        >Сохранить дополнительную услугу</MyBtn
                      >
                      <MyBtn
                        variant="primary"
                        @click="deleteAddedService(service.id)"
                        class="btn-delete"
                      >
                        Удалить дополнительную услугу
                      </MyBtn>
                    </div>
                  </div>
                </MyTransition>
              </div>
            </div>
            <div class="service-actions">
              <MyBtn
                variant="secondary"
                @click="addAddedService"
                type="button"
                class="btn-save btn-add-service"
                :disabled="isAddingAddedService"
              >
                Добавить дополнительную услугу
              </MyBtn>
            </div>
          </div>
        </MyTransition>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watchEffect } from 'vue';
import type { Service, AddedService } from './interfaces/Service';
import { API_URL } from '../../../config';
import Swal from 'sweetalert2';
import ImageUpload from '../../UI/ImageUpload.vue';
import { nextTick } from 'vue';
import MyBtn from '../UI/MyBtn.vue';
import MyQuill from '../UI/MyQuill.vue';
import MyTransition from '../UI/MyTransition.vue';

const localServices = ref<{ main: Service[]; added: AddedService[] }>({
  main: [],
  added: [],
});
const isLoading = ref(true);
const highlightedServiceId = ref<string | null>(null);
const openMainServiceIds = ref<string[]>([]);
const openAddedServiceIds = ref<string[]>([]);
const openAccordionIds = ref<Record<string, boolean>>({});
const isAddingMainService = ref(false);
const isAddingAddedService = ref(false);

const toggleAccordion = (id: string) => {
  openAccordionIds.value[id] = !openAccordionIds.value[id];
};

const toggleMainService = (id: string) => {
  const index = openMainServiceIds.value.indexOf(id);
  if (index === -1) {
    openMainServiceIds.value.push(id);
  } else {
    openMainServiceIds.value.splice(index, 1);
  }
};

const isMainServiceOpen = (id: string) => {
  return openMainServiceIds.value.includes(id);
};

const toggleAddedService = (id: string) => {
  const index = openAddedServiceIds.value.indexOf(id);
  if (index === -1) {
    openAddedServiceIds.value.push(id);
  } else {
    openAddedServiceIds.value.splice(index, 1);
  }
};

const isAddedServiceOpen = (id: string) => {
  return openAddedServiceIds.value.includes(id);
};

function handleImageUpload(
  data: { id: number, path: string; filename: string },
) {
  console.log(data.id, 'ID');
  const service = localServices.value.main.find((s) => s.id === data.id.toString());
  console.log(service);
  if (data.path && localServices.value.main.find((s) => s.id === data.id.toString())) {
    const service = localServices.value.main.find((s) => s.id === data.id.toString());
    if (service) {
      alert(data.path);
      service.image.src = data.path;
      Swal.fire({
        title: 'Успешно!',
        text: `Изображение ${data.filename} загружено.`,
        icon: 'success',
        background: 'white',
        color: 'black',
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
    isAddingAddedService.value = false;
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
    background: 'white',
    color: 'black',
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
          background: 'white',
          color: 'black',
        });
      } catch (error: any) {
        Swal.fire({
          title: 'Ошибка!',
          text: 'Не удалось удалить услугу. ' + error.message,
          icon: 'error',
          background: 'white',
          color: 'black',
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
    background: 'white',
    color: 'black',
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
    isAddingAddedService.value = false;
    Swal.fire({
      title: 'Сохранено!',
      text: isNew
        ? 'Новая дополнительная услуга успешно добавлена.'
        : 'Дополнительная услуга успешно обновлена.',
      icon: 'success',
      background: 'white',
      color: 'black',
    });
  } catch (error: any) {
    Swal.fire({
      title: 'Ошибка!',
      text: error.message,
      icon: 'error',
      background: 'white',
      color: 'black',
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
      background: 'white',
      color: 'black',
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
  isAddingAddedService.value = true;
  highlightedServiceId.value = newId;
  openAddedServiceIds.value.push(newId);

  setTimeout(() => {
    if (highlightedServiceId.value === newId) {
      highlightedServiceId.value = null;
    }
  }, 5000);

  Swal.fire({
    title: 'Добавлена форма для новой дополнительной услуги',
    text: 'Заполните данные и нажмите "Сохранить".',
    icon: 'info',
    background: 'white',
    color: 'black',
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
      background: 'white',
      color: 'black',
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
  isAddingMainService.value = true;
  highlightedServiceId.value = newId;
  openMainServiceIds.value.push(newId);

  setTimeout(() => {
    if (highlightedServiceId.value === newId) {
      highlightedServiceId.value = null;
    }
  }, 5000);

  Swal.fire({
    title: 'Добавлена форма для новой услуги',
    text: 'Заполните данные и нажмите "Сохранить".',
    icon: 'info',
    background: 'white',
    color: 'black',
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
    background: 'white',
    color: 'black',
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
        isAddingMainService.value = false;
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
      background: 'white',
      color: 'black',
    });
  } catch (error: any) {
    console.error(error);
    Swal.fire({
      title: 'Ошибка!',
      text: 'Не удалось сохранить услугу. ' + error.message,
      icon: 'error',
      background: 'white',
      color: 'black',
    });
  }
}

async function deleteService(serviceId: string) {
  // Если это новая, еще не сохраненная услуга, удаляем ее локально
  if (String(serviceId).startsWith('new-')) {
    const index = localServices.value.main.findIndex((s) => s.id === serviceId);
    if (index !== -1) {
      localServices.value.main.splice(index, 1);
      isAddingMainService.value = false;
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
    background: 'white',
    color: 'black',
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
          background: 'white',
          color: 'black',
        });
      } catch (error: any) {
        Swal.fire({
          title: 'Ошибка!',
          text: 'Не удалось удалить услугу. ' + error.message,
          icon: 'error',
          background: 'white',
          color: 'black',
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

watchEffect(() => {
  if (isLoading.value) {
    Swal.fire({
      title: 'Загрузка услуг...',
      text: 'Пожалуйста, подождите',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
      background: 'white',
      color: 'black',
    });
  } else {
    Swal.close();
  }
});
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

.services-admin h1 {
  text-align: center;
  color: #f1f1f1;
}

.theme-dark {
  color: #f1f1f1;
  padding: 20px;
  padding-top: 0;
  max-width: 1440px;
  border-radius: 10px;
}

.space-y-2 {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.service-item {
  border: 1px solid white;
  border-radius: 10px;
  background-color: inherit;
}

.summary-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  font-weight: bold;
  background-color: inherit;
  color: #eee;
  border-radius: 5px;
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
  color: black;
  border-radius: 4px;
  background-color: white;
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

.services-admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  font-weight: bold;
  font-size: 28px;
}

.services-admin-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
  border: 1px solid white;
  border-radius: 5px;
  padding: 10px;
}

.btn-save,
.btn-delete {
  flex: 1;
  width: 100%;
  max-width: 100%;
}

.btn-add-service {
  margin-top: 20px;
  padding: 20px;
}

.my-title {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  font-size: 32px;
  font-weight: bold;
  padding-left: 20px;
  margin: 0;
}

.services-admin-wrapper {
  display: flex;
  flex-direction: column;
  gap: 16px;
  padding: 20px;
}
</style>
