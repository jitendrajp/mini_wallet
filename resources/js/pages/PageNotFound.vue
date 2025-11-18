<template>
    <div class="h-100 not-found">
        <b>404 - Page Not Found</b>
        <p class="mt-2 mb-5">The page you are looking for does not exist.</p>

        <router-link class="px-3 py-1 bg-blue-500 text-white rounded" to="/">
            Go back to Home
        </router-link>
    </div>
</template>

<style scoped>
.not-found {
    text-align: center;
    padding-top: 50px;
}
</style>
