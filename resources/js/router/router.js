import {createRouter, createWebHistory} from 'vue-router';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import Dashboard from '../pages/Dashboard.vue';
import PageNotFound from '../pages/PageNotFound.vue';
import EmailStatus from '../pages/EmailStatus.vue';
import ForgotPassword from '../pages/ForgotPassword.vue';
import ResetPassword from '../pages/ResetPassword.vue';
import api from '../plugins/api.js';

const routes = [
    {path: '/', redirect: '/login'},
    {path: '/login', component: Login},
    {path: '/register', component: Register},
    {path: "/forgot-password", component: ForgotPassword},
    {path: "/reset-password", component: ResetPassword},
    {path: '/dashboard', component: Dashboard, meta: {requiresAuth: true}},
    {path: '/email-status', component: EmailStatus},
    {path: '/:pathMatch(.*)*', name: 'NotFound', component: PageNotFound},
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth) {
        const isAuth = await checkAuth();
        if (!isAuth) {
            next('/login');
            return;
        }
    }
    next();
});

export default router;

async function checkAuth() {
    try {
        const response = await api.get('/me');
        return true;
    } catch (error) {
        if (error.response?.status === 401) {
            return false;
        }
        return false;
    }
}
