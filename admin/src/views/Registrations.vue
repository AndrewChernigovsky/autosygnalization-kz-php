<template>
  <div>
    <h1>Регистрации</h1>
    <div class="registrations">
      <form v-if="!success" @submit.prevent="handleSubmit">
        <input
          v-model="username"
          type="text"
          name="username"
          placeholder="Логин"
        />
        <input v-model="email" type="email" name="email" placeholder="Email" />
        <input
          v-model="password"
          type="password"
          name="password"
          placeholder="Пароль"
        />
        <input
          v-model="password_confirm"
          type="password"
          name="password_confirm"
          placeholder="Подтвердите пароль"
        />
        <button type="submit">Отправить</button>
      </form>
      <div v-if="success">
        <p>Регистрация прошла успешно.</p>
        <p>Перенаправление через {{ countdown }} секунд...</p>
      </div>
      <div v-if="error">
        <p style="color: red">{{ error }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';

const success = ref(false);
const username = ref('');
const email = ref('');
const password = ref('');
const password_confirm = ref('');
const countdown = ref(5);
const router = useRouter();
const error = ref(null);

const handleSubmit = async () => {
  error.value = null;
  if (password.value !== password_confirm.value) {
    error.value = 'Пароли не совпадают';
    return;
  }

  try {
    const response = await fetch('http://localhost:3000/sign_in', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username: username.value,
        email: email.value,
        password: password.value,
        password_confirm: password_confirm.value,
      }),
    });

    const data = await response.json();

    if (response.ok) {
      success.value = true;
    } else {
      error.value = data.message || 'Ошибка регистрации';
    }
  } catch (e) {
    console.error(e);
    error.value = 'Ошибка сети. Проверьте консоль для деталей.';
  }
};

watch(success, (newSuccess) => {
  if (newSuccess) {
    const interval = setInterval(() => {
      countdown.value--;
      if (countdown.value <= 0) {
        clearInterval(interval);
        router.push('/admin');
      }
    }, 1000);
  }
});
</script>

<style scoped></style>
