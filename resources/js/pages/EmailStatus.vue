<template>
    <div class="p-6 text-center">
        <h1 class="text-2xl font-bold mb-4">Email Verification</h1>

        <p v-if="status === 'success'" class="text-green-600">
            Your email has been verified successfully!
        </p>

        <p v-else-if="status === 'already'" class="text-yellow-600">
            Your email was already verified.
        </p>

        <p v-else class="text-red-600">
            Invalid or expired verification link.
        </p>

        <div class="mt-4">
            <router-link class="px-3 py-1 bg-blue-500 text-white rounded" to="/dashboard">
                Dashboard
            </router-link>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import {useRoute} from 'vue-router';

const route = useRoute();
const status = ref('invalid');

onMounted(() => {
    status.value = route.query.status || 'invalid';
});
</script>
