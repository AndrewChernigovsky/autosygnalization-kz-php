<template>
  <div>
    <h1>Регистрации</h1>
    <div class="registrations">
      <form
        action="http://localhost:3000/registrations"
        method="post"
        @submit.prevent="handleSubmit"
      >
        <input type="mail" name="username" placeholder="Логин" />
        <input type="password" name="password" placeholder="Пароль" />
        <input
          type="password"
          name="password_confirm"
          placeholder="Подтвердите пароль"
        />
        <button type="submit">Отправить</button>
      </form>
      <div v-if="success">
        <p>Регистрация прошла успешно</p>
        <Loader />
      </div>
    </div>
  </div>
</template>

<script setup>
import Loader from '../components/Loader.vue';

const success = ref(false);
const username = ref('');
const password = ref('');
const password_confirm = ref('');

const handleSubmit = () => {
  username.value = document.querySelector('input[name="username"]').value;
  password.value = document.querySelector('input[name="password"]').value;
  password_confirm.value = document.querySelector(
    'input[name="password_confirm"]'
  ).value;

  success.value = true;
  console.log(username.value, password.value, password_confirm.value);

  fetch('http://localhost:3000/sign_in', {
    method: 'POST',
    body: JSON.stringify({
      username: username.value,
      password: password.value,
      password_confirm: password_confirm.value,
    }),
  });
};

if (success) {
  setTimeout(() => {
    success.value = false;
    router.push('/admin');
  }, 5000);
}
</script>

<style scoped></style>
