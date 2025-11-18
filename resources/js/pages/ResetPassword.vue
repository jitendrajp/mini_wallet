<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full p-6 bg-white rounded shadow">
            <h2 class="text-2xl mb-4">Reset Password</h2>
            <alert v-if="message" :message="message" type="success"/>
            <alert v-if="errors && Object.keys(errors).length" :message="errors" type="error"/>

            <input v-model="password"
                   class="border p-2 mb-2 w-full"
                   placeholder="New password"
                   type="password"/>

            <input v-model="password_confirmation"
                   class="border p-2 mb-2 w-full"
                   placeholder="Confirm password"
                   type="password"/>

            <button :disabled="loading" class="bg-blue-600 text-white px-4 py-2 rounded" @click="submit">
                {{ loading ? "Please wait..." : "Reset Password" }}
            </button>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import Alert from '../components/Alert.vue';

const route = useRoute();

const password = ref('');
const password_confirmation = ref('');
const message = ref('');
const errors = ref({});
let loading = ref(false);

const submit = async () => {
    loading.value = true;
    message.value = '';
    errors.value = {};
    try {
        const res = await axios.post('/api/reset-password', {
            token: route.query.token,
            email: route.query.email,
            password: password.value,
            password_confirmation: password_confirmation.value,
        });
        password.value = '';
        password_confirmation.value = '';
        loading.value = false;
        message.value = res.data.message;
    } catch (e) {
        password.value = '';
        password_confirmation.value = '';
        loading.value = false;
        errors.value = e.response?.data?.errors || e.response?.data?.message;
    }
};
</script>
