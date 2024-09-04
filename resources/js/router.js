import { createRouter, createWebHistory } from 'vue-router';
import Welcome from '@/Pages/Welcome.vue';
import Dashboard from '@/Pages/Dashboard.vue';
import Login from '@/Pages/Auth/Login.vue';
import Project from '@/Pages/Project.vue';
import Register from '@/Pages/Auth/Register.vue';

const routes = [
    { path: '/home', component: Welcome, name: 'home' },
    { path: '/', component: Dashboard, name: 'dashboard' },
    { path: '/register', component: Register, name: 'register' },

    {
        path: '/project/:boardId', // Update this route to include boardId as a parameter
        component: Project,
        name: 'project',
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
