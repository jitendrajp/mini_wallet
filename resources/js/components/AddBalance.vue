<template>
    <div class="mt-5 w-half">
        <alert v-if="success" :message="success" type="success"/>
        <alert v-if="error" :message="error" type="error"/>
        <form class="space-y-2" @submit.prevent="submit">
            <input v-model="amount" class="w-half p-2 border rounded" placeholder="Enter Amount" step="0.01"
                   type="number"/>
            <button :disabled="loading" class="px-3 py-2 bg-blue-600 xl text-white rounded ml-2">
                {{ loading ? "Please wait..." : "Add Balance" }}
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
