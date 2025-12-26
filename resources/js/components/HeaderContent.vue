<template>
    <span class="text-slate-900">{{ firstWord }}</span>
    <span
        class="bg-gradient-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent"
    >
        {{ secondWord }}
    </span>
</template>

<script setup>
const appName = import.meta.env.VITE_APP_NAME

const words = appName.trim().split(/\s+/).slice(0, 2)

const firstWord = words[0] + ' ' ?? ''
const secondWord = words[1] ?? ''
</script>
