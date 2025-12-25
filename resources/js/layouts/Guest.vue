<template>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center">
        <div class="flex flex-col items-center">
            <GuestHeader />

            <div class="mt-6">
                <router-view />
            </div>

            <div class="mt-6">
                <GuestFooter />
            </div>
        </div>
    </div>
</template>

<script setup>
import GuestHeader from '../components/GuestHeader.vue'
import GuestFooter from '../components/GuestFooter.vue'
</script>
