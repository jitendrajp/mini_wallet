<template>
    <div class="max-w-4xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl">Dashboard</h1>
            <div>
                <button class="px-3 py-1 bg-red-500 text-white rounded" @click="logout">Logout</button>
            </div>
        </div>

        <div class="mb-4">Welcome {{ user?.name }}</div>
        <template v-if="userloading"></template>
        <template v-else>
        <template v-if="user?.email_verified_at">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="col-span-2 bg-white p-4 rounded shadow">
                    <h2 class="text-lg">Balance</h2>
                    <p class="text-3xl mt-2">${{ balance }}</p>
                    <add-balance @done="refresh" v-if="balance <= 1000"></add-balance>
                </div>
                <div class="bg-white p-4 rounded shadow">
                    <transfer-form @done="refresh"/>
                </div>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <transactions-list :transactions="transactions" :user="user"/>
                <div v-if="page < lastPage" class="mt-4 flex justify-center">
                    <button
                        class="px-4 py-2 bg-blue-600 text-white rounded"
                        @click="loadMore" :disabled="loading">{{ loading ? "Please wait..." : "Load More" }}
                    </button>
                </div>
            </div>
        </template>
        <alert v-else :show-close="false"
               message="We have sent an email for verification. Please verify your email first to access Mini Wallet!"
               type="warning">
        </alert>
        </template>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import api from '../plugins/api.js';
import TransferForm from '../components/TransferForm.vue';
import TransactionsList from '../components/TransactionsList.vue';
import {makeEcho} from '../plugins/echo.js';
import Alert from '../components/Alert.vue';
import AddBalance from "../components/AddBalance.vue";

const balance = ref('0.00');
const transactions = ref([]);
let echo = null;
let user = ref({});
const page = ref(1);
const lastPage = ref(1);
let loading = ref(false)
let userloading = ref(false)

async function load(pageNumber = 1) {
    loading.value = true
    try {
        const res = await api.get(`/transactions?page=${pageNumber}`);
        balance.value = res.data.balance;
        if (pageNumber === 1) {
            transactions.value = res.data.transactions.data;
        } else {
            transactions.value.push(...res.data.transactions.data);
        }
        page.value = res.data.transactions.current_page;
        lastPage.value = res.data.transactions.last_page;
        loading.value = false;
    } catch (e) {
        loading.value = false;
        console.error(e);
    }
}

function refresh() {
    load();
}

function logout() {
    localStorage.removeItem('token');
    api.defaults.headers.common['Authorization'] = '';
    window.location.href = '/login';
}

function loadMore() {
    if (page.value < lastPage.value) {
        load(page.value + 1);
    }
}

onMounted(async () => {
    const token = localStorage.getItem('token');
    userloading.value = true
    const me = (await api.get('/me')).data;
    user.value = me
    userloading.value = false
    await load();

    if (token) {
        echo = makeEcho(token);
        window.Echo = echo;
        echo.private(`user.${me.id}`).listen('TransactionCompleted', (e) => {
            /*if (e.transaction) {
                transactions.value.unshift(e.transaction);
                if (e.transaction.sender_id === me.id) {
                    balance.value = (parseFloat(balance.value) -
                        parseFloat(e.transaction.amount) -
                        parseFloat(e.transaction.commission_fee)).toFixed(2);
                } else if (e.transaction.receiver_id === me.id) {
                    balance.value = (parseFloat(balance.value) +
                        parseFloat(e.transaction.amount)).toFixed(2);
                }
            }*/
        }).error((err) => {
            console.error("Channel subscription error:", err);
        });
    }
});
</script>
