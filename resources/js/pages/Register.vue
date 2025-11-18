<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full p-6 bg-white rounded shadow">
            <h2 class="text-2xl mb-4">Register</h2>
            <alert v-if="errors && Object.keys(errors).length" :message="errors" type="error"/>
            <form class="space-y-4" @submit.prevent="submit">
                <input v-model="name" class="w-full p-2 border rounded" placeholder="Name"/>
                <input v-model="email" class="w-full p-2 border rounded" placeholder="Email" type="email"/>
                <input v-model="password" class="w-full p-2 border rounded" placeholder="Password" type="password"/>
                <input v-model="password_confirmation" class="w-full p-2 border rounded"
                       placeholder="Confirm Password" type="password"/>
                <div class="flex justify-between items-center">
                    <button :disabled="loading" class="px-4 py-2 bg-blue-600 text-white rounded">
                        {{ loading ? "Please wait..." : "Register" }}
                    </button>
                    <router-link class="text-sm text-blue-600" to="/login">Already Registered?</router-link>
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

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const router = useRouter();
const errors = ref({})
let loading = ref(false)

async function submit() {
    errors.value = {};
    loading.value = true;
    try {
        const res = await api.post('/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value
        });
        const token = res.data.token;
        localStorage.setItem('token', token);
        setToken(token);
        loading.value = false;
        router.push('/dashboard');
        errors.value = e.response?.data?.errors || 'Registered. Check your email for verification link.';
    } catch (e) {
        loading.value = false;
        errors.value = e.response?.data?.errors || 'Registration failed';
    }
}
</script>
