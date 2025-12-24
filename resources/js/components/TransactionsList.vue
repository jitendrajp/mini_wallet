<template>
    <div>
        <div v-if="transactions.length === 0" class="rounded-md border border-dashed border-slate-300
           py-8 text-center text-sm text-slate-600">
            No transactions yet
        </div>

        <ul v-else class="divide-y divide-slate-200">
            <li v-for="t in transactions" :key="t.id" class="flex items-center justify-between py-4">
                <div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-900">
                        <span>
                            {{ t.sender_id === user.id ? t.receiver?.name : t.sender?.name }}
                        </span>
                        <span :class="t.sender_id === user.id
                                ? 'bg-gradient-to-r from-red-50 to-rose-100 text-red-700 ring-red-200'
                                : 'bg-gradient-to-r from-green-50 to-emerald-100 text-green-700 ring-green-200'"
                              class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset">
                        <svg
                            class="h-3.5 w-3.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                v-if="t.sender_id === user.id"
                                d="M7 17L17 7m0 0H7m10 0v10"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                            <path
                                v-else
                                d="M17 7L7 17m0 0h10m-10 0V7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                        </svg>

                        {{ t.sender_id === user.id ? 'Sent' : 'Received' }}
                    </span>
                    </div>

                    <div class="mt-1 text-sm text-slate-600">
                        Amount:
                        <span class="font-medium text-slate-900">
                            ${{ t.amount }}
                        </span>
                        <span v-if="t.sender_id === user.id" class="text-xs text-slate-400">
                            (Fee: ${{ t.commission_fee }})
                        </span>
                    </div>
                </div>

                <div class="text-right text-xs text-slate-600 whitespace-nowrap">
                    {{ formatDate(t.created_at) }}
                </div>
            </li>
        </ul>
    </div>

</template>

<script setup>
defineProps({
    transactions: {type: Array, required: true},
    user: {type: Object, required: true},
});

const formatDate = (date) => {
    return new Date(date).toLocaleString([], {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });
};
</script>

<style scoped>
ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
</style>

