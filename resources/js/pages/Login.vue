<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full p-6 bg-white rounded shadow">
            <h2 class="text-2xl mb-4">Login</h2>
            <alert v-if="error" :message="error" type="error"/>
            <form class="space-y-4" @submit.prevent="submit">
                <input v-model="email" class="w-full p-2 border rounded" placeholder="Email" type="email"/>
                <input v-model="password" class="w-full p-2 border rounded" placeholder="Password" type="password"/>
                <div class="flex justify-between items-center">
                    <button :disabled="loading" class="px-4 py-2 bg-blue-600 text-white rounded">
                        {{ loading ? "Please wait..." : "Login" }}
                    </button>
                    <router-link class="text-sm text-blue-600" to="/forgot-password">Forgot Password?</router-link>
                </div>
                <hr>
                <div class="flex justify-between items-center">
                    <router-link class="text-sm text-blue-600" to="/register">Want to Register?</router-link>
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
