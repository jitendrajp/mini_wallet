<template>
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-semibold text-slate-900">
            Welcome back
        </h2>
        <p class="mt-1 text-sm text-slate-600">
            Sign in to continue
        </p>

        <alert v-if="error" :message="error" class="mt-4" type="error" />

        <form class="mt-6 space-y-4" @submit.prevent="submit">
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
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6
                           A2.25 2.25 0 005.25 5.25v13.5
                           A2.25 2.25 0 007.5 21h6
                           a2.25 2.25 0 002.25-2.25V15
                           M18 12h-9m0 0l3-3m-3 3l3 3"
                    />
                </svg>

                {{ buttonLabel }}
            </button>

            <div class="flex justify-end">
                <router-link
                    class="text-sm text-blue-600 hover:underline"
                    to="/forgot-password"
                >
                    Forgot password?
                </router-link>
            </div>

            <div class="relative my-6">
                <div class="h-px bg-slate-200"></div>
            </div>

            <div class="text-center text-sm text-slate-600">
                Don’t have an account?
                <router-link
                    class="font-medium text-blue-600 hover:underline"
                    to="/register"
                >
                    Sign up
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

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

const buttonLabel = computed(() => {
    return loading.value ? 'Signing in...' : 'Sign in'
})

function resetError() {
    error.value = ''
}

async function submit() {
    resetError()
    loading.value = true

    try {
        const { data } = await api.post('/login', {
            email: email.value,
            password: password.value,
        })

        const token = data.token

        localStorage.setItem('token', token)
        setToken(token)

        router.push('/dashboard')
    } catch (e) {
        error.value = e?.response?.data?.error || 'Login failed'
    } finally {
        loading.value = false
    }
}
</script>

