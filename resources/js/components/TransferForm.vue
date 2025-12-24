<template>
    <div>
        <h3 class="mb-3 text-sm font-semibold text-slate-900">
            Send money
        </h3>

        <alert v-if="success" :message="success" class="mb-3" type="success"/>
        <alert v-if="error" :message="error" class="mb-3" type="error"/>

        <form class="space-y-4" @submit.prevent="submit">
            <div class="relative">
                <label class="mb-1 block text-xs font-medium text-slate-600">
                    Recipient
                </label>

                <input
                    v-model="search"
                    autocomplete="off"
                    class="w-full rounded-md border border-slate-300
                           px-3 py-2 text-sm
                           focus:border-blue-600 focus:ring-4
                           focus:ring-blue-600/20 outline-none"
                    placeholder="Search by name or email"
                    @input="searchUsers"/>

                <ul
                    v-if="users.length"
                    class="absolute z-20 mt-1 max-h-52 w-full overflow-auto rounded-md border border-slate-200 bg-white shadow-lg">
                    <li v-for="user in users" :key="user.id" class="cursor-pointer px-3 py-2 text-sm hover:bg-slate-50"
                        @click="selectUser(user)">
                        <div class="font-medium text-slate-900">
                            {{ user.name }}
                        </div>
                        <div class="text-xs text-slate-600">
                            {{ user.email }}
                        </div>
                    </li>
                </ul>
            </div>

            <input :value="receiver_id" type="hidden"/>

            <div>
                <label class="mb-1 block text-xs font-medium text-slate-600">
                    Amount
                </label>

                <input
                    v-model="amount"
                    class="w-full rounded-md border border-slate-300
                           px-3 py-2 text-sm
                           focus:border-blue-600 focus:ring-4
                           focus:ring-blue-600/20 outline-none"
                    placeholder="0.00"
                    step="0.01"
                    type="number"/>
            </div>

            <div class="flex justify-end">
                <button :disabled="loading || !receiver_id || !amount"
                        class="flex items-center gap-2 rounded-md bg-gradient-to-r
                           from-blue-600 to-blue-700
                           px-4 py-2 text-sm font-medium
                           text-white shadow-md transition
                           hover:opacity-95 disabled:opacity-60">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 17L17 7m0 0H7m10 0v10" />
                    </svg>
                    {{ loading ? "Sending..." : "Send money" }}
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

    const res = await api.get('/users', {params: {q: search.value}});
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
