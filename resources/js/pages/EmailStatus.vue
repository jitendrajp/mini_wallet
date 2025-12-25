<template>
    <div class="flex items-center justify-center bg-slate-50">
        <div
            class="w-full max-w-md rounded-md bg-white p-8 shadow-xl shadow-slate-200/60 text-center"
        >
            <div
                :class="statusClasses"
                class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full"
            >
                <span class="text-2xl">
                    {{ statusIcon }}
                </span>
            </div>

            <h1 class="text-xl font-semibold text-slate-900">
                Email verification
            </h1>

            <p v-if="isSuccess" class="mt-2 text-sm text-green-600">
                Your email has been verified successfully.
            </p>

            <p v-else-if="isAlreadyVerified" class="mt-2 text-sm text-yellow-600">
                Your email address was already verified.
            </p>

            <p v-else class="mt-2 text-sm text-red-600">
                The verification link is invalid or has expired.
            </p>

            <div class="mt-6">
                <router-link
                    class="inline-flex items-center justify-center
                           rounded-md bg-gradient-to-r
                           from-blue-600 to-blue-700
                           px-5 py-2.5 text-sm font-medium text-white
                           shadow-md transition hover:opacity-95"
                    to="/dashboard"
                >
                    Go to dashboard
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const status = ref('invalid')

const isSuccess = computed(() => status.value === 'success')
const isAlreadyVerified = computed(() => status.value === 'already')

const statusIcon = computed(() => {
    if (isSuccess.value) return '✓'
    if (isAlreadyVerified.value) return 'ℹ'
    return '✕'
})

const statusClasses = computed(() => {
    if (isSuccess.value) {
        return 'bg-green-100 text-green-600'
    }

    if (isAlreadyVerified.value) {
        return 'bg-yellow-100 text-yellow-600'
    }

    return 'bg-red-100 text-red-600'
})

onMounted(() => {
    status.value = route.query.status || 'invalid'
})
</script>

