<template>
  <div class="login-page">
    <h1>Вход</h1>
    <form @submit.prevent="handleLogin">
      <input v-model="username" placeholder="Логин" required />
      <input v-model="password" type="password" placeholder="Пароль" required />
      <button type="submit">Войти</button>
    </form>
  </div>
</template>

<script>
import { useUserStore } from '@/stores/user';

export default {
  setup() {
    const userStore = useUserStore(); // Доступ к хранилищу пользователя

    const username = ref('');
    const password = ref('');

    const handleLogin = async () => {
      // Пример проверки логина
      if (username.value === 'admin' && password.value === 'admin123') {
        // Устанавливаем данные пользователя в хранилище
        userStore.setUser({ name: 'Admin', role: 'admin' }, 'adminToken');
        // Перенаправляем в зависимости от роли
        if (userStore.role === 'admin') {
          router.push('/admin');
        }
      } else {
        alert('Неверный логин или пароль');
      }
    };

    return { username, password, handleLogin };
  },
};
</script>

<style>
.login-page {
  max-width: 400px;
  margin: 50px auto;
  text-align: center;
}
</style>
