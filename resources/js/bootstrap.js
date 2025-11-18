import axios from 'axios';
import Pusher from 'pusher-js';

axios.defaults.baseURL = '/api';

const token = localStorage.getItem('token');
if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

window.axios = axios;
window.Pusher = Pusher;
