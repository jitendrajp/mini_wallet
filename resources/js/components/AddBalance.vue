<template>
    <div class="mt-6 max-w-sm">
        <alert v-if="success" :message="success" class="mb-3" type="success"/>
        <alert v-if="error" :message="error" class="mb-3" type="error"/>

        <form class="flex items-end gap-3" @submit.prevent="submit">
            <div class="flex-1">
                <label class="mb-1 block text-xs font-medium text-slate-600">
                    Amount
                </label>
                <input
                    v-model="amount"
                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-blue-600 focus:ring-4 focus:ring-blue-600/20 outline-none"
                    placeholder="0.00"
                    step="0.01"
                    type="number"
                />
            </div>

            <button :disabled="loading || !amount"
                    class="flex items-center gap-2 rounded-md bg-gradient-to-r
                           from-blue-600 to-blue-700
                           px-4 py-2 text-sm font-medium
                           text-white shadow-md transition
                           hover:opacity-95 disabled:opacity-60">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4" />
                </svg>

                {{ loading ? "Adding..." : "Add balance" }}
            </button>
        </form>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import api from '../plugins/api.js';
import Alert from './Alert.vue';

const emit = defineEmits(['done']);
const amount = ref('');
const success = ref('');
const error = ref('');
let loading = ref(false);

async function submit() {
    success.value = '';
    error.value = '';
    loading.value = true;
    try {
        await api.post('/add_balance', {balance: amount.value});
        amount.value = '';
        emit('done');
        loading.value = false;
        success.value = 'Balance Added';
    } catch (e) {
        loading.value = false;
        error.value = e.response?.data?.errors || e.response?.data?.error || 'Transfer failed';
    }
}
</script>
