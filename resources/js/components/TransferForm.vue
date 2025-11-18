<template>
    <div>
        <h3 class="font-medium mb-2">Send Money</h3>
        <alert v-if="success" :message="success" type="success"/>
        <alert v-if="error" :message="error" type="error"/>
        <form class="space-y-2" @submit.prevent="submit">
            <input v-model="receiver_id" class="w-full p-2 border rounded" placeholder="Enter Recipient User ID"
                   step="0.01"
                   type="number"/>
            <input v-model="amount" class="w-full p-2 border rounded" placeholder="Enter Amount" step="0.01"
                   type="number"/>
            <div class="flex justify-end">
                <button :disabled="loading" class="px-3 py-1 bg-blue-600 text-white rounded">
                    {{ loading ? "Please wait..." : "Send" }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import api from '../plugins/api.js';
import Alert from './Alert.vue';

const emit = defineEmits(['done']);
const receiver_id = ref(null);
const amount = ref('');
const success = ref('');
const error = ref('');
let loading = ref(false);

async function submit() {
    success.value = '';
    error.value = '';
    loading.value = true;
    try {
        await api.post('/transactions', {receiver_id: receiver_id.value, amount: amount.value});
        amount.value = '';
        receiver_id.value = null;
        emit('done');
        loading.value = false;
        success.value = 'Transfer completed';
    } catch (e) {
        loading.value = false;
        error.value = e.response?.data?.errors || e.response?.data?.error || 'Transfer failed';
    }
}
</script>
