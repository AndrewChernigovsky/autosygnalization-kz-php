<template>
  <div>
    <h1>Профиль</h1>
    <p>Поменять имя</p>
    <MyInput type="text" variant="primary" v-model="username" name="username" />
    <p>Поменять пароль</p>
    <div class="password-input">
      <MyInput
        :type="showPassword ? 'text' : 'password'"
        variant="primary"
        v-model="password"
        name="password"
      />
      <button @click="togglePasswordVisibility" class="toggle-password">
        {{ showPassword ? '👁️' : '👁️‍🗨️' }}
      </button>
    </div>
    <div class="buttons">
      <MyBtn variant="primary" @click="updateProfile">Изменить</MyBtn>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';
import MyInput from '../components/UI/MyInput.vue';
import MyBtn from '../components/UI/MyBtn.vue';

interface ProfileI {
  username?: string;
  password?: string;
  email?: string;
  role?: string;
  is_active?: string;
}

const data = ref<ProfileI | null>(null);
const password = ref('');
// const email = ref('');
const username = ref('');
const showPassword = ref(false);

function togglePasswordVisibility() {
  showPassword.value = !showPassword.value;
}

const loadProfile = async () => {
  try {
    const response = await fetchWithCors('/profile');
    if (response.success) {
      data.value = response.data;

      if (response.data.username) {
        username.value = response.data.username;
      }
      if (response.data.password) {
        password.value = response.data.password;
      }
    } else {
      console.error('Ошибка загрузки профиля:', response);
    }
  } catch (error) {
    console.error('Ошибка загрузки профиля:', error);
  }
};

const updateProfile = async () => {
  if (!username.value && !password.value) {
    Swal.fire({
      icon: 'warning',
      title: 'Внимание',
      text: 'Нечего обновлять. Заполните хотя бы одно поле.',
    });
    return;
  }
  if (username.value && !username.value.trim()) {
    Swal.fire({
      icon: 'warning',
      title: 'Внимание',
      text: 'Имя пользователя не может быть пустым.',
    });
    return;
  }
  try {
    const formData = new FormData();
    if (password.value) formData.append('password', password.value);
    if (username.value) formData.append('username', username.value);

    const response = await fetchWithCors(
      '/server/php/admin/api/profile/profile.php',
      {
        method: 'POST',
        body: formData,
      }
    );
    if (response.success) {
      Swal.fire({
        icon: 'success',
        title: 'Успешно!',
        text: 'Данные профиля обновлены',
        timer: 2000,
        showConfirmButton: false,
      });
      password.value = '';
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: response.error || 'Ошибка при обновлении данных',
      });
    }
  } catch (error: any) {
    console.error('Ошибка сохранения:', error);
    Swal.fire({
      icon: 'error',
      title: 'Ошибка',
      text: error.message || 'Ошибка при обновлении данных',
    });
  }
};

// Загружаем профиль при монтировании
onMounted(() => {
  loadProfile();
});
</script>

<style scoped>
.my-input-wrapper {
  width: 30%;
}

.password-input {
  width: 100%;
  position: relative;
  display: inline-block;
}

.toggle-password {
  position: absolute;
  left: 30%;
  top: 50%;
  transform: translate(-140%, -50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.2em;
  padding: 0 5px;
}

input[type='password'],
input[type='text'] {
  padding-right: 30px;
  /* Оставляем место для кнопки */
}

.buttons {
  display: flex;
  gap: 16px;
  margin-top: 20px;
}

.btn-primary {
  background: #007bff;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.btn-primary:hover {
  background: #0056b3;
}

.btn-logout {
  background: #dc3545;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.btn-logout:hover {
  background: #c82333;
}
</style>
