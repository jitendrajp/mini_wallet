<template>
    <div class="flex items-center justify-center bg-slate-50">
        <div
            class="w-full max-w-md rounded-md bg-white p-8 shadow-xl shadow-slate-200/60">
            <h2 class="text-2xl font-semibold text-slate-900">
                Forgot password?
            </h2>
            <p class="mt-1 text-sm text-slate-600">
                We’ll email you a link to reset your password
            </p>

            <alert
                v-if="message"
                :message="message"
                class="mt-4"
                type="success"/>

            <alert
                v-if="errors && Object.keys(errors).length"
                :message="errors"
                class="mt-4"
                type="error"/>

            <input
                v-model="email"
                class="mt-6 w-full rounded-md border border-slate-300
                       px-4 py-2.5 text-sm
                       focus:border-blue-600 focus:ring-4
                       focus:ring-blue-600/20 outline-none"
                placeholder="Email address"
                type="email"/>

            <div class="mt-6 space-y-4">
                <button
                    :disabled="loading"
                    class="flex w-full items-center justify-center gap-2 w-full rounded-md bg-gradient-to-r
                           from-blue-600 to-blue-700
                           px-4 py-2 text-sm font-medium
                           text-white shadow-md transition
                           hover:opacity-95 disabled:opacity-60"
                    @click="submit">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21.75 6.75v10.5
                 a2.25 2.25 0 01-2.25 2.25h-15
                 a2.25 2.25 0 01-2.25-2.25V6.75
                 m19.5 0A2.25 2.25 0 0019.5 4.5h-15
                 A2.25 2.25 0 002.25 6.75
                 m19.5 0L12 12.75 2.25 6.75" />
                    </svg>
                    {{ loading ? "Sending link..." : "Send reset link" }}
                </button>

                <div class="text-center">
                    <router-link
                        class="text-sm text-blue-600 hover:underline"
                        to="/login">
                        Back to login
                    </router-link>
                </div>
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
