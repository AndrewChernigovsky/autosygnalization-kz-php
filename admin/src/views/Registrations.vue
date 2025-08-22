<template>
  <div>
    <h1>Профиль</h1>
    <p>Поменять имя</p>
    <input type="text" v-model="username" name="username" />
    <p>Поменять пароль</p>
    <div class="password-input">
      <input
        :type="showPassword ? 'text' : 'password'"
        v-model="password"
        name="password"
      />
      <button @click="togglePasswordVisibility" class="toggle-password">
        {{ showPassword ? '👁️' : '👁️‍🗨️' }}
      </button>
    </div>
    <p>Поменять email</p>
    <input type="email" v-model="email" name="email" />
    <div class="buttons">
      <button @click="updateProfile" class="btn-primary">Изменить</button>
      <button @click="logout" class="btn-logout">Выйти из аккаунта</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import fetchWithCors from '../utils/fetchWithCors';

interface ProfileI {
  username?: string;
  password?: string;
  email?: string;
  role?: string;
  is_active?: string;
}

const data = ref<ProfileI | null>(null);
const password = ref('');
const email = ref('');
const username = ref('');
const showPassword = ref(false);

function togglePasswordVisibility() {
  showPassword.value = !showPassword.value;
}

const loadProfile = async () => {
  try {
    const response = await fetchWithCors(
      '/server/php/admin/api/profile/profile.php'
    );
    if (response.success) {
      data.value = response.data;

      if (response.data.email) {
        email.value = response.data.email;
      }
      if (response.data.username) {
        username.value = response.data.username;
      }
    } else {
      console.error('Ошибка загрузки профиля:', response);
    }
  } catch (error) {
    console.error('Ошибка загрузки профиля:', error);
  }
};

const updateProfile = async () => {
  if (!username.value && !email.value && !password.value) {
    Swal.fire({
      icon: 'warning',
      title: 'Внимание',
      text: 'Нечего обновлять. Заполните хотя бы одно поле.',
    });
    return;
  }
  if (!username.value && !email.value) {
    Swal.fire({
      icon: 'warning',
      title: 'Внимание',
      text: 'Поля username и email не могут быть пустыми.',
    });
    return;
  }
  try {
    const formData = new FormData();
    if (password.value) formData.append('password', password.value);
    if (email.value) formData.append('email', email.value);
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

const logout = async () => {
  try {
    // Показываем подтверждение выхода
    const result = await Swal.fire({
      icon: 'question',
      title: 'Выход из аккаунта',
      text: 'Вы уверены, что хотите выйти?',
      showCancelButton: true,
      confirmButtonText: 'Да, выйти',
      cancelButtonText: 'Отмена',
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
    });

    if (result.isConfirmed) {
      // Выполняем выход через Google Auth
      const response = await fetch('/google_auth?action=logout', {
        method: 'GET',
      });

      if (response.ok) {
        // Очищаем локальные данные
        data.value = null;
        username.value = '';
        email.value = '';
        password.value = '';

        // Показываем сообщение об успешном выходе
        Swal.fire({
          icon: 'success',
          title: 'Вы вышли из аккаунта',
          text: 'Перенаправление на страницу входа...',
          timer: 2000,
          showConfirmButton: false,
        });

        // Перенаправляем на страницу входа
        setTimeout(() => {
          window.location.href = 'http://localhost:3000/login';
        }, 2000);
      } else {
        throw new Error('Ошибка при выходе');
      }
    }
  } catch (error: any) {
    console.error('Ошибка выхода:', error);
    Swal.fire({
      icon: 'error',
      title: 'Ошибка',
      text: 'Не удалось выйти из аккаунта. Попробуйте еще раз.',
    });
  }
};

// Загружаем профиль при монтировании
onMounted(() => {
  loadProfile();
});
</script>

<style scoped>
.password-input {
  position: relative;
  display: inline-block;
}

.toggle-password {
  position: absolute;
  right: 5px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.2em;
  padding: 0 5px;
}

input[type='password'],
input[type='text'] {
  padding-right: 30px; /* Оставляем место для кнопки */
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
