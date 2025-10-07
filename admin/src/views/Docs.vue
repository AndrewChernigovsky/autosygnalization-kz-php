<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';
import MyBtn from '../components/UI/MyBtn.vue';

interface DocItem {
  id: number;
  path: string;
  key: string;
  file?: File;
}

const docs = ref<DocItem[]>([]);
const isUpdating = ref<number | null>(null);

const handleFileChange = (event: Event, doc: DocItem) => {
  const target = event.target as HTMLInputElement;

  if (target.files && target.files[0]) {
    const file = target.files[0];

    // Валидация файла
    if (
      file.type !== 'text/plain' &&
      !file.name.toLowerCase().endsWith('.txt')
    ) {
      Swal.fire({
        title: 'Неподдерживаемый формат',
        text: 'Пожалуйста, выберите только .txt файл',
        icon: 'error',
        confirmButtonColor: '#3085d6',
      });
      target.value = '';
      doc.file = undefined;
      return;
    }

    // Проверка размера файла (максимум 5MB)
    if (file.size > 5 * 1024 * 1024) {
      Swal.fire({
        title: 'Файл слишком большой',
        text: 'Максимальный размер файла: 5MB',
        icon: 'error',
        confirmButtonColor: '#3085d6',
      });
      target.value = '';
      doc.file = undefined;
      return;
    }

    doc.file = file;
    // Сбрасываем value input, чтобы при повторной загрузке того же файла событие change сработало
    target.value = '';
  }
};

const updateDoc = async (doc: DocItem) => {
  if (!doc.key.trim()) {
    Swal.fire({
      title: 'Ошибка',
      text: 'Ключ документа не может быть пустым',
      icon: 'error',
      confirmButtonColor: '#3085d6',
    });
    return;
  }

  isUpdating.value = doc.id;

  try {
    const formData = new FormData();
    formData.append('id', doc.id.toString());
    formData.append('key', doc.key.trim());

    if (doc.file) {
      formData.append('file', doc.file);
    }

    const response = await fetchWithCors(
      '/server/php/admin/api/docs/docs.php',
      {
        method: 'POST',
        body: formData,
      }
    );

    if (response.success) {
      Swal.fire({
        title: 'Успешно!',
        text: 'Документ обновлен',
        icon: 'success',
        confirmButtonColor: '#28a745',
        timer: 2000,
        showConfirmButton: false,
      });

      // Очищаем выбранный файл
      doc.file = undefined;

      // Обновляем список
      await getDocs();
    } else {
      Swal.fire({
        title: 'Ошибка',
        text: response.error || 'Не удалось обновить документ',
        icon: 'error',
        confirmButtonColor: '#3085d6',
      });
    }
  } catch (error) {
    console.error(error);
    Swal.fire({
      title: 'Ошибка',
      text: 'Не удалось обновить документ',
      icon: 'error',
      confirmButtonColor: '#3085d6',
    });
  } finally {
    isUpdating.value = null;
  }
};

const getDocs = async () => {
  try {
    const response = await fetchWithCors('/server/php/admin/api/docs/docs.php');
    if (response.success) {
      docs.value = response.data;
      console.log(docs.value);
    } else {
      Swal.fire({
        title: 'Ошибка',
        text: response.error || 'Не удалось загрузить документы',
        icon: 'error',
        confirmButtonColor: '#3085d6',
      });
    }
  } catch (error) {
    console.error(error);
    Swal.fire({
      title: 'Ошибка',
      text: 'Не удалось загрузить документы',
      icon: 'error',
      confirmButtonColor: '#3085d6',
    });
  }
};

function getDocName(key: string) {
  switch (key) {
    case 'deal':
      return 'Договор';
    case 'delivary':
      return 'Доставка';
    case 'policy':
      return 'Политика';
  }
  return key;
}

onMounted(async () => {
  await getDocs();
});
</script>

<template>
  <div class="docs-page">
    <div class="docs-header">
      <h1 class="docs-title">Управление документами</h1>
    </div>

    <div class="docs-container">
      <div class="docs-item" v-for="doc in docs" :key="doc.id">
        <div class="doc-info">
          <div class="doc-field">
            <h3 class="title m-0">{{ getDocName(doc.key) }}</h3>
            <label class="field-label">Новый файл:</label>
            <div class="file-input-wrapper">
              <input
                type="file"
                @change="handleFileChange($event, doc)"
                accept=".txt,text/plain"
                class="file-input"
                :id="'file-' + doc.id"
              />
              <label :for="'file-' + doc.id" class="file-input-label">
                <span class="file-icon">📄</span>
                <span class="file-text">{{
                  doc.file ? doc.file.name : 'Выбрать файл'
                }}</span>
              </label>
            </div>
          </div>

          <div class="doc-field" v-if="doc.path">
            <label class="field-label">Текущий файл:</label>
            <div class="current-file-info">
              <span class="file-icon">📋</span>
              <span class="file-path">{{ doc.path }}</span>
            </div>
          </div>
        </div>

        <div class="doc-actions">
          <MyBtn
            variant="secondary"
            @click="updateDoc(doc)"
            :disabled="!doc.key.trim()"
            class="update-btn"
          >
            <span class="btn-icon">💾</span>
            Обновить
          </MyBtn>
        </div>
      </div>

      <div v-if="docs.length === 0" class="no-docs">
        <div class="no-docs-icon">📚</div>
        <p>Документы не найдены</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.docs-page {
  padding: 20px;
  max-width: 100%;
  min-height: 100vh;
}

.docs-header {
  text-align: center;
  margin-bottom: 40px;
  padding: 30px;
  background: inherit;
  color: white;
  border-radius: 12px;
}

.docs-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #ffffff;
  margin: 0 0 10px 0;
}

.docs-subtitle {
  font-size: 1.1rem;
  color: #6c757d;
  margin: 0;
}

.docs-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.docs-item {
  background: inherit;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  border: 1px solid #2c3e50;
  color: white;
  transition: all 0.3s ease;
}

.docs-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.doc-info {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 25px;
}

.doc-field {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.field-label {
  font-weight: 600;
  color: #495057;
  font-size: 0.95rem;
}

.doc-input {
  padding: 12px 16px;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
  background: #f8f9fa;
}

.doc-input:focus {
  outline: none;
  border-color: #007bff;
  background: white;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.file-input-wrapper {
  position: relative;
}

.file-input {
  position: absolute;
  opacity: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

.file-input-label {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 16px;
  border: 2px dashed #dee2e6;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.file-input-label:hover {
  border-color: #007bff;
  background: #e3f2fd;
}

.file-icon {
  font-size: 1.2rem;
}

.file-text {
  color: #6c757d;
  font-weight: 500;
}

.current-file-info {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 16px;
  background: #e8f5e8;
  border: 1px solid #c3e6c3;
  border-radius: 8px;
  color: #155724;
}

.file-path {
  font-family: 'Courier New', monospace;
  font-size: 0.9rem;
  word-break: break-all;
}

.doc-actions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
}

.update-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.update-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-icon {
  font-size: 1.1rem;
}

.no-docs {
  text-align: center;
  padding: 60px 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.no-docs-icon {
  font-size: 4rem;
  margin-bottom: 20px;
  opacity: 0.5;
}

.no-docs p {
  color: #6c757d;
  font-size: 1.1rem;
  margin: 0;
}

/* Адаптивность */
@media (max-width: 768px) {
  .docs-page {
    padding: 15px;
  }

  .docs-header {
    padding: 20px;
    margin-bottom: 30px;
  }

  .docs-title {
    font-size: 2rem;
  }

  .docs-item {
    padding: 20px;
  }

  .doc-actions {
    justify-content: center;
  }

  .update-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>
