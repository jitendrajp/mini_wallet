<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full p-6 bg-white rounded shadow">
            <h2 class="text-2xl mb-4">Forgot Password</h2>
            <alert v-if="message" :message="message" type="success"/>
            <alert v-if="errors && Object.keys(errors).length" :message="errors" type="error"/>
            <input v-model="email" class="border p-2 mb-2 w-full" placeholder="Email"/>

            <div class="flex justify-between items-center">
                <button :disabled="loading" class="bg-blue-600 text-white px-4 py-2 rounded" @click="submit">
                    {{ loading ? "Please wait..." : "Send Reset Link" }}
                </button>
                <router-link class="text-sm text-blue-600" to="/login">Back to Login</router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import axios from 'axios';
import Alert from '../components/Alert.vue';

const email = ref('');
const message = ref('');
const errors = ref({});
let loading = ref(false);

const submit = async () => {
    message.value = '';
    errors.value = {};
    loading.value = true;
    try {
        const res = await axios.post('/api/forgot-password', {email: email.value});
        loading.value = false;
        message.value = res.data.message;
    } catch (e) {
        loading.value = false;
        errors.value = e.response?.data?.errors || e.response?.data?.message;
    }
};
</script>
