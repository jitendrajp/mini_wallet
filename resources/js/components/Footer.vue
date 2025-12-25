<template>
    <footer
        class="bottom-0 left-0 right-0 z-50
               transition-shadow duration-200"
    >
        <div class="mx-auto max-w-5xl px-6 py-6 text-center text-sm text-slate-600">
            © {{ currentYear }} Mini Wallet. All rights reserved.
        </div>
    </footer>
</template>


<script setup>
import { computed } from 'vue'

const currentYear = computed(() => {
    return new Date().getFullYear()
})
</script>

