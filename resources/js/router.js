import { createRouter, createWebHistory } from 'vue-router';
import Welcome from '@/Pages/Home.vue';
import Dashboard from '@/Pages/Dashboard.vue';
import Login from '@/Pages/Auth/Login.vue';
import Project from '@/Pages/Project.vue';
import Register from '@/Pages/Auth/Register.vue';

// Function to check if the user is authenticated
function isAuthenticated() {
  // Check if the token is stored in local storage
  const token = localStorage.getItem('token');
  return !!token; // Return true if the token exists, otherwise false
}

const routes = [
  { path: '/home', component: Welcome, name: 'home', meta: { requiresAuth: true } },
  { path: '/', component: Dashboard, name: 'dashboard' },
  { path: '/register', component: Register, name: 'register' },
  {
    path: '/project/:boardName',
    name: 'project',
    component: Project,
    meta: { requiresAuth: true },
    props: (route) => ({ boardName: route.params.boardName,workspace: route.params.workspace }),
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  // Catch-all route for undefined paths
  { path: '/:pathMatch(.*)*', redirect: to => {
      // Redirect based on authentication status
      return isAuthenticated() ? { name: 'home' } : { name: 'dashboard' };
  }},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guard to check for authentication
router.beforeEach((to, from, next) => {
  const isUserAuthenticated = isAuthenticated();

  // Redirect based on authentication status and route
  if (isUserAuthenticated) {
    if (to.name === 'login' || to.name === 'register' || to.name === 'dashboard') {
      next({ name: 'home' }); // Redirect authenticated users to home
    } else {
      next(); // Allow navigation to other routes
    }
  } else {
    if (to.name !== 'login' && to.name !== 'register' && to.name !== 'dashboard') {
      next({ name: 'dashboard' }); // Redirect unauthenticated users to dashboard
    } else {
      next(); // Allow navigation to allowed routes
    }
  }
});

export default router;
