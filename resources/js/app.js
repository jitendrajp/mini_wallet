import {createApp} from 'vue';
import App from './layouts/App.vue';
import router from './router/router.js';
import {setToken} from './plugins/api.js';
import '../css/app.css';

const token = localStorage.getItem('token');
if (token) setToken(token);
createApp(App).use(router).mount('#app');
