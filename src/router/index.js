import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/Login.vue';
import Admin from '@/views/Admin.vue';
import Teacher from '@/views/Teacher.vue';
import Student from '@/views/Student.vue';
import {useUserStore} from "@/stores/user.js";

const routes = [
    { path: '/', name: 'Login', component: Login }, // Маршрут по умолчанию
    { path: '/admin', name: 'Admin', component: Admin },
    { path: '/teacher', name: 'Teacher', component: Teacher },
    { path: '/student', name: 'Student', component: Student },
    { path: '/:catchAll(.*)', redirect: '/' }, // Перенаправление на Login при неправильном URL
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const userStore = useUserStore();

    if (to.meta.requiresAuth && !userStore.isAuthenticated) {
        // Если маршрут требует авторизации и пользователь не авторизован
        next('/login');
    } else if (to.meta.role && userStore.userRole !== to.meta.role) {
        // Если маршрут требует определенную роль и роль пользователя не соответствует
        alert('У вас нет доступа к этой странице');
        next('/login');
    } else {
        // Если все проверки пройдены
        next();
    }
});

export default router;
