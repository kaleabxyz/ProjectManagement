import { createRouter, createWebHistory } from 'vue-router';
import Welcome from './Pages/Welcome.vue';
import Dashboard from './Pages/Dashboard.vue';
import Project from './Pages/Project.vue';

const routes = [
    { path: '/', component: Welcome, name: 'home' },
    { path: '/dashboard', component: Dashboard, name: 'dashboard' },
    { path: '/project', component: Project, name: 'project' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
