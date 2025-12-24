<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <header
            class="fixed top-0 left-0 right-0 z-50
    bg-white
    border-b border-slate-200
    shadow-md
    transition-shadow duration-200">
            <div
                class="mx-auto max-w-5xl px-6 py-4 flex items-center justify-center">
                <div
                    class="text-lg font-semibold tracking-tight text-slate-900 hover:opacity-90 transition select-none">
                    Mini Wallet
                </div>
            </div>
        </header>

        <main class="pt-20">
            <transition mode="out-in" name="fade">
                <router-view/>
            </transition>
        </main>

        <footer class=" bottom-0 left-0 right-0 z-50
                    bg-white
                    border-b border-slate-200
                    shadow-md
                    transition-shadow duration-200">
            <div class="mx-auto max-w-5xl px-6 py-6 text-center text-sm text-slate-500">
                © {{ new Date().getFullYear() }} Mini Wallet. All rights reserved.
            </div>
        </footer>
    </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
