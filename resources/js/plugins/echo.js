import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

export function makeEcho(token) {
    return new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_KEY,
        cluster: import.meta.env.VITE_PUSHER_CLUSTER,
        forceTLS: false,
        authEndpoint: '/api/broadcasting/auth',
        auth: {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
            },
        },
    })
}
