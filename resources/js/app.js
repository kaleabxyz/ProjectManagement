import axios from 'axios';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { ZiggyVue } from '../../vendor/tightenco/ziggy'; // If using Ziggy

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const app = createApp(App);

app.use(router); // Use Vue Router
app.use(ZiggyVue); // Use Ziggy if needed

app.mount('#app');
