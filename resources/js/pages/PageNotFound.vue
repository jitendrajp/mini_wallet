<template>
    <div
        class="text-center w-full max-w-md rounded-md bg-white p-8 shadow-xl shadow-slate-200/60"
    >
        <h1 class="text-6xl font-bold text-blue-600">
            404
        </h1>

        <h2 class="mt-2 text-xl font-semibold text-slate-900">
            Page not found
        </h2>

        <p class="mt-2 text-sm text-slate-600">
            The page you’re looking for doesn’t exist or has been moved.
        </p>

        <div class="mt-6">
            <router-link
                class="inline-flex items-center justify-center
                       rounded-md bg-gradient-to-r
                       from-blue-600 to-blue-700
                       px-5 py-2.5 text-sm font-medium text-white
                       shadow-md transition hover:opacity-95"
                to="/"
            >
                Go back home
            </router-link>
        </div>
    </div>
</template>
