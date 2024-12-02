import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null, // Информация о текущем пользователе
        token: '',  // JWT-токен или другой способ авторизации
        role: '',   // Роль пользователя: 'admin', 'teacher', 'student'
    }),
    actions: {
        // Установка информации о пользователе
        setUser(user, token) {
            this.user = user;
            this.token = token;
            this.role = user.role; // Роль может быть из данных пользователя
        },
        // Сброс данных при выходе
        logout() {
            this.user = null;
            this.token = '';
            this.role = '';
        },
    },
});
