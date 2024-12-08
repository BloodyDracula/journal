import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null,
        token: null,
        role: null, // Добавляем роль пользователя
    }),
    actions: {
        setUser(user, token) {
            this.user = user;
            this.token = token;
            this.role = user.role; // Сохраняем роль пользователя
            localStorage.setItem('authToken', token); // Сохраняем токен в локальное хранилище
            localStorage.setItem('userRole', user.role); // Сохраняем роль пользователя в локальное хранилище
        },
        logout() {
            this.user = null;
            this.token = null;
            this.role = null; // Очищаем роль пользователя
            localStorage.removeItem('authToken');
            localStorage.removeItem('userRole');
        }
    },
    getters: {
        isAuthenticated: (state) => !!state.token,
        userRole: (state) => state.role, // Геттер для получения роли пользователя
    }
});
