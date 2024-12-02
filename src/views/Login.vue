<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore(); // Доступ к хранилищу пользователя
const router = useRouter(); // Доступ к маршрутизатору

const username = ref('');
const password = ref('');

const handleLogin = async () => {
  // Пример проверки логина
  if (username.value === 'admin' && password.value === 'admin123') {
    // Устанавливаем данные пользователя в хранилище
    userStore.setUser({ name: 'Admin', role: 'admin' }, 'adminToken');

    // Перенаправляем в зависимости от роли
    if (userStore.user.role === 'admin') {
      await router.push('/admin');
    }
  } else {
    alert('Неверный логин или пароль');
  }
};
</script>

<template>
  <div class="login-page">
    <h2>Авторизация</h2>
    <form @submit.prevent="handleLogin">
      <div>
        <label for="username">Логин:</label>
        <input id="username" v-model="username" type="text" />
      </div>
      <div>
        <label for="password">Пароль:</label>
        <input id="password" v-model="password" type="password" />
      </div>
      <button type="submit">Войти</button>
    </form>
  </div>
</template>

<style>
/* Стили для страницы авторизации */
.login-page {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.login-page h2 {
  text-align: center;
  margin-bottom: 20px;
}
.login-page form {
  display: flex;
  flex-direction: column;
}
.login-page form div {
  margin-bottom: 15px;
}
.login-page form label {
  margin-bottom: 5px;
  font-weight: bold;
}
.login-page form input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.login-page button {
  padding: 10px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.login-page button:hover {
  background-color: #0056b3;
}
</style>
