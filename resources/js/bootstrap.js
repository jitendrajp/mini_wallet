import axios from 'axios'
import Pusher from 'pusher-js'

axios.defaults.baseURL = '/api'

initializeAxiosAuth()

window.axios = axios
window.Pusher = Pusher

function initializeAxiosAuth() {
    const token = localStorage.getItem('token')

    if (token) {
        axios.defaults.headers.common.Authorization = `Bearer ${token}`
    }
}
