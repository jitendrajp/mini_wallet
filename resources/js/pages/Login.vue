<template>
    <div class="min-h-screen flex items-center justify-center bg-slate-50">
        <div
            class="w-full max-w-md rounded-md bg-white p-8 shadow-xl shadow-slate-200/60">
            <h2 class="text-2xl font-semibold text-slate-900">
                Welcome back
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Sign in to continue
            </p>

            <alert v-if="error" :message="error" class="mt-4" type="error"/>

            <form class="mt-6 space-y-4" @submit.prevent="submit">
                <input
                    v-model="email"
                    class="w-full rounded-md border border-slate-300 bg-slate-50
                             px-4 py-2.5 text-sm
                             focus:border-blue-600 focus:ring-4
                             focus:ring-blue-600/20 outline-none"
                    placeholder="Email address"
                    type="email"
                />

                <input
                    v-model="password"
                    class="w-full rounded-md border border-slate-300 bg-slate-50
                             px-4 py-2.5 text-sm
                             focus:border-blue-600 focus:ring-4
                             focus:ring-blue-600/20 outline-none"
                    placeholder="Password"
                    type="password"
                />

                <button
                    :disabled="loading"
                    class="w-full rounded-md bg-gradient-to-r
                           from-blue-600 to-blue-700
                           px-4 py-2 text-sm font-medium
                           text-white shadow-md transition
                           hover:opacity-95 disabled:opacity-60"
                >
                    {{ loading ? "Signing in..." : "Login" }}
                </button>

                <div class="flex justify-end">
                    <router-link class="text-sm text-blue-600 hover:underline" to="/forgot-password">
                        Forgot password?
                    </router-link>
                </div>

                <div class="relative my-6">
                    <div class="h-px bg-slate-200"></div>
                </div>

                <div class="text-center text-sm text-slate-600">
                    Don’t have an account?
                    <router-link class="font-medium text-blue-600 hover:underline" to="/register">
                        Register
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import api, {setToken} from '../plugins/api.js';
import {useRouter} from 'vue-router';
import Alert from '../components/Alert.vue';

const email = ref('');
const password = ref('');
const router = useRouter();
const error = ref('');
let loading = ref(false);

async function submit() {
    error.value = '';
    loading.value = true;
    try {
        const res = await api.post('/login', {email: email.value, password: password.value});
        const token = res.data.token;
        localStorage.setItem('token', token);
        setToken(token);
        loading.value = false;
        router.push('/dashboard');
    } catch (e) {
        loading.value = false;
        error.value = e.response?.data?.error || 'Login failed';
    }
}
</script>
