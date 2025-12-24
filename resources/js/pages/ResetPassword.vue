<template>
    <template>
        <div class="flex items-center justify-center bg-slate-50">
            <div
                class="w-full max-w-md rounded-md bg-white p-8 shadow-xl shadow-slate-200/60">
                <h2 class="text-2xl font-semibold text-slate-900">
                    Reset your password
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Enter a new password for your account
                </p>

                <alert
                    v-if="message"
                    :message="message"
                    class="mt-4"
                    type="success"
                />
                <alert
                    v-if="errors && Object.keys(errors).length"
                    :message="errors"
                    class="mt-4"
                    type="error"
                />

                <input
                    v-model="password"
                    class="mt-6 w-full rounded-md border border-slate-300
                       px-4 py-2.5 text-sm
                       focus:border-blue-600 focus:ring-4
                       focus:ring-blue-600/20 outline-none"
                    placeholder="New password"
                    type="password"
                />

                <input
                    v-model="password_confirmation"
                    class="mt-4 w-full rounded-md border border-slate-300
                       px-4 py-2.5 text-sm
                       focus:border-blue-600 focus:ring-4
                       focus:ring-blue-600/20 outline-none"
                    placeholder="Confirm password"
                    type="password"
                />

                <!-- Button -->
                <button
                    :disabled="loading"
                    class="mt-6 w-full rounded-md bg-gradient-to-r
                       from-blue-600 to-blue-700
                       py-2.5 text-sm font-medium text-white
                       shadow-md transition
                       hover:opacity-95 disabled:opacity-60"
                    @click="submit">
                    {{ loading ? "Resetting password..." : "Reset password" }}
                </button>
            </div>
        </div>
    </template>
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
