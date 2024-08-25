import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { ZiggyVue } from '../../vendor/tightenco/ziggy'; // If using Ziggy

const app = createApp(App);

app.use(router); // Use Vue Router
app.use(ZiggyVue); // Use Ziggy if needed

app.mount('#app');
