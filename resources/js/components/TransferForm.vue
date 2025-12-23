<template>
    <div>
        <h3 class="font-medium mb-2">Send Money</h3>
        <alert v-if="success" :message="success" type="success"/>
        <alert v-if="error" :message="error" type="error"/>
        <form class="space-y-2" @submit.prevent="submit">
            <div class="relative">
                <input
                    v-model="search"
                    autocomplete="off"
                    class="w-full p-2 border rounded"
                    placeholder="Search Recipient User"
                    @input="searchUsers"
                />

                <ul
                    v-if="users.length"
                    class="absolute z-10 w-full bg-white border rounded max-h-48 overflow-auto"
                >
                    <li
                        v-for="user in users"
                        :key="user.id"
                        class="p-2 cursor-pointer hover:bg-gray-100"
                        @click="selectUser(user)"
                    >
                        {{ user.name }} <span class="text-gray-400">{{ user.email }}</span>
                    </li>
                </ul>
            </div>

            <input :value="receiver_id" type="hidden"/>
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
const search = ref('');
const users = ref([]);

const searchUsers = async () => {
    if (search.value.length < 1) {
        users.value = [];
        return;
    }

    const res = await api.get('/users', { params: {q: search.value} });
    users.value = res.data;
}

const selectUser = (user) => {
    search.value = user.name;
    receiver_id.value = user.id;
    users.value = [];
}

async function submit() {
    success.value = '';
    error.value = '';
    loading.value = true;
    try {
        await api.post('/transactions', {receiver_id: receiver_id.value, amount: amount.value});
        amount.value = '';
        receiver_id.value = null;
        search.value = null;
        emit('done');
        loading.value = false;
        success.value = 'Transfer completed';
    } catch (e) {
        loading.value = false;
        error.value = e.response?.data?.errors || e.response?.data?.error || 'Transfer failed';
    }
}
</script>
