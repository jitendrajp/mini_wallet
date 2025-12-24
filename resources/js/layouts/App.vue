<template>
    <div class="min-h-screen bg-slate-50 text-slate-900 justify-center">
        <Header/>

        <main class="mx-auto max-w-5xl px-6">
            <router-view v-slot="{ Component }">
                <transition name="fade" mode="out-in">
                    <component :is="Component" />
                </transition>
            </router-view>
        </main>

        <Footer/>
    </div>
</template>

<script setup>
import Header from '../components/Header.vue';
import Footer from '../components/Footer.vue';
</script>
