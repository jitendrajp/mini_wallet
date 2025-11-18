<template>
    <div>
        <h3 class="font-medium mb-4 text-lg">Transactions</h3>

        <div v-if="transactions.length === 0" class="text-sm text-gray-500">
            No transactions yet.
        </div>

        <ul v-else>
            <li
                v-for="t in transactions" :key="t.id" class="border-b py-3 flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-2 text-s">
                        <span>{{ t.sender_id === user.id ? t.receiver?.name : t.sender?.name }}</span>
                        <span
                            :class="t.sender_id === user.id ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                            class="text-sm font-semibold px-2 py-0.5 rounded">
                            {{ t.sender_id === user.id ? 'Sent' : 'Received' }}
                        </span>
                    </div>

                    <div class="text-s text-gray-600 mt-1">
                        Amount: ${{ t.amount }} {{ t.sender_id === user.id ? '(Fee: $' + t.commission_fee + ')' : '' }}
                    </div>
                </div>

                <div class="text-s text-gray-500 whitespace-nowrap">
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

