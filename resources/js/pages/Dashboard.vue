<template>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-5xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
                    <p class="text-sm text-slate-500">
                        Welcome back, {{ user?.name }}
                    </p>
                </div>

                <button class="rounded-md bg-red-500
                           from-red-600 to-red-700
                           px-4 py-2 text-sm font-medium
                           text-white shadow-md transition
                           hover:opacity-95 disabled:opacity-60"
                        @click="logout">
                    Logout
                </button>
            </div>

            <template v-if="userLoading"></template>

            <template v-else>
                <template v-if="user?.email_verified_at">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="md:col-span-2 rounded-md bg-white p-6 shadow">
                            <h2 class="text-sm font-medium text-slate-500">
                                Current balance
                            </h2>

                            <p class="mt-2 text-3xl font-semibold text-slate-900">
                                ${{ balance }}
                            </p>

                            <div class="mt-4">
                                <add-balance v-if="balance <= 1000" @done="refresh"/>
                            </div>
                        </div>

                        <div class="rounded-md bg-white p-6 shadow">
                            <h2 class="text-sm font-medium text-slate-500 mb-3">
                                Quick transfer
                            </h2>
                            <transfer-form @done="refresh"/>
                        </div>
                    </div>

                    <div class="rounded-md bg-white p-6 shadow">
                        <h2 class="mb-4 text-lg font-semibold text-slate-900">
                            Recent transactions
                        </h2>

                        <transactions-list :transactions="transactions" :user="user"/>

                        <div v-if="page < lastPage" class="mt-6 flex justify-center">
                            <button
                                :disabled="loading"
                                class="rounded-md bg-gradient-to-r
                           from-blue-600 to-blue-700
                           px-4 py-2 text-sm font-medium
                           text-white shadow-md transition
                           hover:opacity-95 disabled:opacity-60"
                                @click="loadMore">
                                {{ loading ? "Loading..." : "Load more" }}
                            </button>
                        </div>
                    </div>
                </template>

                <alert
                    v-else
                    :show-close="false"
                    message="We have sent a verification email. Please verify your email to access Mini Wallet."
                    type="warning"
                />
            </template>
        </div>
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
let loading = ref(false);
let userLoading = ref(false);

async function load(pageNumber = 1) {
    loading.value = true;
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

function hasTransaction(id) {
    return transactions.value.some(t => t.id === id);
}

onMounted(async () => {
    const token = localStorage.getItem('token');
    userLoading.value = true;
    const me = (await api.get('/me')).data;
    user.value = me;
    userLoading.value = false;
    await load();

    if (token) {
        echo = makeEcho(token);
        window.Echo = echo;
        echo.private(`user.${me.id}`).listen('TransactionCompleted', (e) => {
            if (e.transaction) {
                const tx = e.transaction;
                if (!tx) return;

                if (hasTransaction(tx.id)) return;

                transactions.value.unshift(tx);
                if (e.transaction.sender_id === me.id) {
                    balance.value = (parseFloat(balance.value) -
                        parseFloat(e.transaction.amount) -
                        parseFloat(e.transaction.commission_fee)).toFixed(2);
                } else if (e.transaction.receiver_id === me.id) {
                    balance.value = (parseFloat(balance.value) +
                        parseFloat(e.transaction.amount)).toFixed(2);
                }
            }
        }).error((err) => {
            console.error("Channel subscription error:", err);
        });
    }
})
</script>
