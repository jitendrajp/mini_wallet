<template>
    <div
        class="w-full max-w-md rounded-md bg-white p-8 shadow-xl shadow-slate-200/60"
    >
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
            v-if="hasErrors"
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

        <button
            :disabled="loading"
            class="mt-6 w-full rounded-md bg-gradient-to-r
                   from-blue-600 to-blue-700
                   py-2.5 text-sm font-medium text-white
                   shadow-md transition
                   hover:opacity-95 disabled:opacity-60"
            @click="submit"
        >
            {{ buttonLabel }}
        </button>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import Alert from '../components/Alert.vue'

const route = useRoute()

const password = ref('')
const password_confirmation = ref('')
const message = ref('')
const errors = ref({})
const loading = ref(false)

const hasErrors = computed(() => {
    return errors.value && Object.keys(errors.value).length > 0
})

const buttonLabel = computed(() => {
    return loading.value
        ? 'Resetting password...'
        : 'Reset password'
})

function resetForm() {
    password.value = ''
    password_confirmation.value = ''
}

function resetMessages() {
    message.value = ''
    errors.value = {}
}

async function submit() {
    resetMessages()
    loading.value = true

    try {
        const { data } = await axios.post('/api/reset-password', {
            token: route.query.token,
            email: route.query.email,
            password: password.value,
            password_confirmation: password_confirmation.value,
        })

        resetForm()
        message.value = data.message
    } catch (e) {
        resetForm()
        errors.value =
            e?.response?.data?.errors ||
            e?.response?.data?.message ||
            {}
    } finally {
        loading.value = false
    }
}
</script>
