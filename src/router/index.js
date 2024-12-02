import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/Login.vue';
import Admin from '@/views/Admin.vue';
import Teacher from '@/views/Teacher.vue';
import Student from '@/views/Student.vue';

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

export default router;
