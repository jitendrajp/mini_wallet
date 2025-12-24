<template>
    <div>
        <div v-if="transactions.length === 0" class="rounded-md border border-dashed border-slate-300
           py-8 text-center text-sm text-slate-500">
            No transactions yet
        </div>

        <ul v-else class="divide-y divide-slate-200">
            <li v-for="t in transactions" :key="t.id" class="flex items-center justify-between py-4">
                <div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-900">
                        <span>
                            {{ t.sender_id === user.id ? t.receiver?.name : t.sender?.name }}
                        </span>
                        <span
                            :class="t.sender_id === user.id ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
                            class="rounded-full px-2 py-0.5 text-xs font-medium">
                            {{ t.sender_id === user.id ? 'Sent' : 'Received' }}
                            </span>
                    </div>

                    <div class="mt-1 text-sm text-slate-500">
                        Amount:
                        <span class="font-medium text-slate-900">
                            ${{ t.amount }}
                        </span>
                        <span v-if="t.sender_id === user.id" class="text-xs text-slate-400">
                            (Fee: ${{ t.commission_fee }})
                        </span>
                    </div>
                </div>

                <div class="text-right text-xs text-slate-500 whitespace-nowrap">
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

