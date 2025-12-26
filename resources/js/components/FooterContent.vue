<template>
    © {{ currentYear }} {{appName}}.
    by
    <a
        href="https://github.com/jitendrajp"
        target="_blank"
        rel="noopener noreferrer"
        class="font-medium text-slate-700 hover:text-slate-900 underline-offset-4 hover:underline"
    >
        Jitendra Patel
    </a>
</template>

<script setup>
import { computed } from 'vue'

const currentYear = computed(() => {
    return new Date().getFullYear()
})

const appName = import.meta.env.VITE_APP_NAME
</script>
