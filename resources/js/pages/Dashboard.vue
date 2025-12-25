<template>
    <div class="bg-slate-50">
        <div class="max-w-5xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
                    <p class="text-sm text-slate-600">
                        Welcome back, {{ user?.name }}
                    </p>
                </div>

                <button
                    class="flex items-center gap-2 rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700
                           transition-all duration-200 hover:border-transparent hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 hover:text-white"
                    @click="logout"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6 A2.25 2.25 0 005.25 5.25v13.5 A2.25 2.25 0 007.5 21h6 a2.25 2.25 0 002.25-2.25V15 M18 12h-9m0 0l3-3m-3 3l3 3"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                        />
                    </svg>
                    Logout
                </button>
            </div>

            <template v-if="userLoading"></template>

            <template v-else>
                <template v-if="isVerified">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="md:col-span-2 rounded-md bg-white p-6 shadow">
                            <h2 class="text-sm font-medium text-slate-600">
                                Current balance
                            </h2>

                            <p class="mt-2 text-3xl font-semibold text-slate-900">
                                ${{ balance }}
                            </p>

                            <div class="mt-4">
                                <add-balance v-if="canAddBalance" @done="refresh" />
                            </div>
                        </div>

                        <div class="rounded-md bg-white p-6 shadow">
                            <h2 class="text-sm font-medium text-slate-600 mb-3">
                                Quick transfer
                            </h2>
                            <transfer-form @done="refresh" />
                        </div>
                    </div>

                    <div class="rounded-md bg-white p-6 shadow">
                        <h2 class="mb-4 text-lg font-semibold text-slate-900">
                            Recent transactions
                        </h2>

                        <transactions-list
                            :transactions="transactions"
                            :user="user"
                        />

                        <div v-if="canLoadMore" class="mt-6 flex justify-center">
                            <button
                                :disabled="loading"
                                class="rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-sm font-medium text-white shadow-md transition hover:opacity-95 disabled:opacity-60"
                                @click="loadMore"
                            >
                                {{ loadMoreLabel }}
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
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import api from '../plugins/api'
import { makeEcho } from '../plugins/echo'
import TransferForm from '../components/TransferForm.vue'
import TransactionsList from '../components/TransactionsList.vue'
import Alert from '../components/Alert.vue'
import AddBalance from '../components/AddBalance.vue'

const balance = ref('0.00')
const transactions = ref([])
const user = ref({})
const page = ref(1)
const lastPage = ref(1)
const loading = ref(false)
const userLoading = ref(false)

let echo = null

const isVerified = computed(() => !!user.value?.email_verified_at)
const canAddBalance = computed(() => parseFloat(balance.value) <= 1000)
const canLoadMore = computed(() => page.value < lastPage.value)
const loadMoreLabel = computed(() =>
    loading.value ? 'Loading...' : 'Load more'
)

async function loadTransactions(pageNumber = 1) {
    loading.value = true

    try {
        const { data } = await api.get(`/transactions?page=${pageNumber}`)

        balance.value = data.balance

        if (pageNumber === 1) {
            transactions.value = data.transactions.data
        } else {
            transactions.value.push(...data.transactions.data)
        }

        page.value = data.transactions.current_page
        lastPage.value = data.transactions.last_page
    } catch (e) {
        console.error(e)
    } finally {
        loading.value = false
    }
}

function refresh() {
    loadTransactions()
}

function loadMore() {
    if (canLoadMore.value) {
        loadTransactions(page.value + 1)
    }
}

function logout() {
    localStorage.removeItem('token')
    delete api.defaults.headers.common['Authorization']
    window.location.href = '/login'
}

function hasTransaction(id) {
    return transactions.value.some(t => t.id === id)
}

function updateBalanceFromTransaction(tx) {
    const current = parseFloat(balance.value)

    if (tx.sender_id === user.value.id) {
        balance.value = (
            current -
            parseFloat(tx.amount) -
            parseFloat(tx.commission_fee)
        ).toFixed(2)
    } else if (tx.receiver_id === user.value.id) {
        balance.value = (
            current + parseFloat(tx.amount)
        ).toFixed(2)
    }
}

onMounted(async () => {
    const token = localStorage.getItem('token')

    userLoading.value = true
    const { data } = await api.get('/me')
    user.value = data
    userLoading.value = false

    await loadTransactions()

    if (!token) return

    echo = makeEcho(token)
    window.Echo = echo

    echo.private(`user.${data.id}`)
        .listen('TransactionCompleted', (e) => {
            const tx = e?.transaction
            if (!tx || hasTransaction(tx.id)) return

            transactions.value.unshift(tx)
            updateBalanceFromTransaction(tx)
        })
        .error(err => {
            console.error('Channel subscription error:', err)
        })
})

onBeforeUnmount(() => {
    if (echo) {
        echo.disconnect()
        echo = null
    }
})
</script>
