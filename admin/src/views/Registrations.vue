<template>
  <div>
    <h1>Профиль</h1>
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
      <button @click="updateProfile">Изменить</button>
      <a href="/google_auth?action=logout">Выйти из аккаунта</a>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';

const password = ref('');
const email = ref('');
const data = ref(null);
const showPassword = ref(false); // Состояние видимости пароля

function togglePasswordVisibility() {
  showPassword.value = !showPassword.value;
}

// Получение токена из localStorage
// const getAuthToken = () => {
//   return localStorage.getItem('auth_token');
// };

// Загрузка данных профиля (GET)
const loadProfile = () => {
  // const token = getAuthToken();
  // if (!token) {
  //   console.error('Токен не найден');
  //   return;
  // }

  fetch('http://localhost:3000/profile', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
    },
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.email) {
        email.value = data.email;
      }
      // ❌ Пароль НЕ возвращается — это безопасно
    })
    .catch((err) => {
      console.error('Ошибка загрузки профиля:', err);
    });
};

// Обновление профиля (PATCH/POST)
const updateProfile = () => {
  // Формируем payload: только если поле заполнено
  const payload = {};
  if (password.value) payload.password = password.value;
  if (email.value) payload.email = email.value;

  if (Object.keys(payload).length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Внимание',
      text: 'Нечего обновлять. Заполните хотя бы одно поле.',
    });
    return;
  }

  // Показываем лоадер
  Swal.fire({
    title: 'Обновление данных...',
    text: 'Пожалуйста, подождите',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  fetch('http://localhost:3000/profile', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(payload),
  })
    .then((res) => res.json())
    .then((data) => {
      Swal.close(); // Закрываем лоадер
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Успешно!',
          text: 'Данные профиля обновлены',
          timer: 2000,
          showConfirmButton: false,
        });
        password.value = ''; // Очищаем поле пароля после успешного изменения
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Ошибка',
          text: data.message || 'Ошибка при обновлении данных',
        });
      }
    })
    .catch((err) => {
      Swal.close(); // Закрываем лоадер в случае ошибки
      console.error('Ошибка сохранения:', err);
      Swal.fire({
        icon: 'error',
        title: 'Ошибка сети',
        text: 'Не удалось подключиться к серверу',
      });
    });
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
</style>
