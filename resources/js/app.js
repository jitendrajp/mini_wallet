import { createApp } from 'vue'
import App from './App.vue'
import router from './router/router'
import { setToken } from './plugins/api'
import '../css/app.css'

initializeAuth()

createApp(App)
    .use(router)
    .mount('#app')

function initializeAuth() {
    const token = localStorage.getItem('token')

    if (token) {
        setToken(token)
    }
}
