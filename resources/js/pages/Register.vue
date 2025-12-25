<template>
    <div class="w-full max-w-md rounded-md bg-white p-8 shadow-xl shadow-slate-200/60">
        <h2 class="text-2xl font-semibold text-slate-900">
            Create your account
        </h2>
        <p class="mt-1 text-sm text-slate-600">
            Sign up to get started
        </p>

        <alert
            v-if="hasErrors"
            :message="errors"
            class="mt-4"
            type="error"
        />

        <!-- Form -->
        <form class="mt-6 space-y-4" @submit.prevent="submit">
            <input
                v-model="name"
                class="w-full rounded-md border border-slate-300
                       px-4 py-2.5 text-sm
                       focus:border-blue-600 focus:ring-4
                       focus:ring-blue-600/20 outline-none"
                placeholder="Full name"
            />

            <input
                v-model="email"
                class="w-full rounded-md border border-slate-300
                       px-4 py-2.5 text-sm
                       focus:border-blue-600 focus:ring-4
                       focus:ring-blue-600/20 outline-none"
                placeholder="Email address"
                type="email"
            />

            <input
                v-model="password"
                class="w-full rounded-md border border-slate-300
                       px-4 py-2.5 text-sm
                       focus:border-blue-600 focus:ring-4
                       focus:ring-blue-600/20 outline-none"
                placeholder="Password"
                type="password"
            />

            <input
                v-model="password_confirmation"
                class="w-full rounded-md border border-slate-300
                       px-4 py-2.5 text-sm
                       focus:border-blue-600 focus:ring-4
                       focus:ring-blue-600/20 outline-none"
                placeholder="Confirm password"
                type="password"
            />

            <button
                :disabled="loading"
                class="flex justify-center gap-2 w-full rounded-md bg-gradient-to-r
                       from-blue-600 to-blue-700
                       px-4 py-2 text-sm font-medium
                       text-white shadow-md transition
                       hover:opacity-95 disabled:opacity-60"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M18 14.25v1.5m0 0v1.5m0-1.5h1.5m-1.5 0H15
                           m-6-4.5a4.5 4.5 0 100-9
                           a4.5 4.5 0 000 9
                           z
                           m-3 9a6 6 0 0112 0"
                    />
                </svg>

                {{ buttonLabel }}
            </button>

            <div class="text-center text-sm text-slate-600">
                Already have an account?
                <router-link
                    class="font-medium text-blue-600 hover:underline"
                    to="/login"
                >
                    Sign in
                </router-link>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import api, { setToken } from '../plugins/api'
import Alert from '../components/Alert.vue'

const router = useRouter()

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const errors = ref({})
const loading = ref(false)

const hasErrors = computed(() => {
    return errors.value && Object.keys(errors.value).length > 0
})

const buttonLabel = computed(() => {
    return loading.value ? 'Creating account...' : 'Sign Up'
})

function resetErrors() {
    errors.value = {}
}

async function submit() {
    resetErrors()
    loading.value = true

    try {
        const { data } = await api.post('/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        })

        const token = data.token
        localStorage.setItem('token', token)
        setToken(token)

        router.push('/dashboard')
    } catch (e) {
        errors.value =
            e?.response?.data?.errors ||
            'Registration failed'
    } finally {
        loading.value = false
    }
}
</script>
