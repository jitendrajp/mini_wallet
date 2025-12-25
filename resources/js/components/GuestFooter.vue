<template>
    <footer class="pb-4 text-sm text-slate-600 text-center">
        © {{ currentYear }} Mini Wallet. All rights reserved.
    </footer>
</template>

<script setup>
import { computed } from 'vue'
const currentYear = computed(() => new Date().getFullYear())
</script>
