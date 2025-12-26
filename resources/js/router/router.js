import { createRouter, createWebHistory } from 'vue-router'
import api from '../plugins/api'

import DashboardLayout from '../layouts/Dashboard.vue'
import GuestLayout from '../layouts/Guest.vue'

import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import ForgotPassword from '../pages/ForgotPassword.vue'
import ResetPassword from '../pages/ResetPassword.vue'
import PageNotFound from '../pages/PageNotFound.vue'
import Dashboard from '../pages/Dashboard.vue'
import EmailStatus from '../pages/EmailStatus.vue'

const routes = [
    {
        path: '/',
        component: GuestLayout,
        children: [
            {
                path: '',
                redirect: '/login',
            },
            { path: 'login', component: Login, meta: { guestOnly: true } },
            { path: 'register', component: Register, meta: { guestOnly: true } },
            { path: 'forgot-password', component: ForgotPassword, meta: { guestOnly: true }  },
            { path: 'reset-password', component: ResetPassword, meta: { guestOnly: true }  },
            { path: 'email-status', component: EmailStatus},
            {
                path: ':pathMatch(.*)*',
                name: 'NotFound',
                component: PageNotFound,
            },
        ],
    },

    {
        path: '/',
        component: DashboardLayout,
        children: [
            { path: 'dashboard', component: Dashboard, meta: { requiresAuth: true } }
        ],
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to) => {
    const isAuthenticated = await checkAuth()

    if (to.meta.guestOnly && isAuthenticated) {
        return '/dashboard'
    }

    if (to.meta.requiresAuth && !isAuthenticated) {
        return '/login'
    }

    return true
})

export default router

async function checkAuth() {
    try {
        await api.get('/me')
        return true
    } catch {
        return false
    }
}
