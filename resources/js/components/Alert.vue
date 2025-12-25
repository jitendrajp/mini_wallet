<template>
    <div
        v-if="visible"
        :class="[
            'flex items-start gap-3 rounded-md p-2 mb-1 text-sm shadow-sm transition-opacity duration-300',
            typeClasses
        ]"
        role="alert"
    >
        <div class="flex-1">
            <div v-if="isStringMessage" class="text-sm">
                <slot>{{ message }}</slot>
            </div>

            <div v-else-if="isObjectMessage" class="space-y-1">
                <template v-for="(errors, field) in message" :key="field">
                    <div
                        v-for="(error, index) in errors"
                        :key="index"
                        class="text-sm"
                    >
                        {{ error }}
                    </div>
                </template>
            </div>
        </div>

        <button
            v-if="showClose"
            class="text-lg leading-none opacity-60 hover:opacity-100 transition"
            @click="close"
        >
            <span
                :class="iconBg"
                class="mt-0.5 flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold"
            >
                {{ icon }}
            </span>
        </button>
    </div>
</template>

<script setup>
import { ref, computed, watch, onBeforeUnmount } from 'vue'

const props = defineProps({
    type: { type: String, default: 'info' },
    message: { type: [String, Object], default: '' },
    duration: { type: Number, default: 5000 },
    showClose: { type: Boolean, default: true },
})

const visible = ref(true)
let timer = null

const TYPE_MAP = {
    success: {
        classes: 'bg-green-50 text-green-700 border border-green-200',
        icon: '✓',
        iconBg: 'bg-green-200 text-green-800',
    },
    error: {
        classes: 'bg-red-50 text-red-700 border border-red-200',
        icon: '✕',
        iconBg: 'bg-red-200 text-red-800',
    },
    warning: {
        classes: 'bg-yellow-50 text-yellow-700 border border-yellow-200',
        icon: '!',
        iconBg: 'bg-yellow-200 text-yellow-800',
    },
    info: {
        classes: 'bg-blue-50 text-blue-700 border border-blue-200',
        icon: 'ℹ',
        iconBg: 'bg-blue-200 text-blue-800',
    },
}

const typeConfig = computed(() => TYPE_MAP[props.type] || TYPE_MAP.info)
const typeClasses = computed(() => typeConfig.value.classes)
const icon = computed(() => typeConfig.value.icon)
const iconBg = computed(() => typeConfig.value.iconBg)

const isStringMessage = computed(
    () => typeof props.message === 'string' && props.message.length > 0
)

const isObjectMessage = computed(
    () =>
        typeof props.message === 'object' &&
        props.message !== null &&
        Object.keys(props.message).length > 0
)

function close() {
    visible.value = false
    clearTimer()
}

function clearTimer() {
    if (timer) {
        clearTimeout(timer)
        timer = null
    }
}

function startAutoClose() {
    clearTimer()

    if (props.duration > 0 && props.showClose) {
        timer = setTimeout(close, props.duration)
    }
}

watch(
    () => props.message,
    (newVal) => {
        const hasMessage =
            (typeof newVal === 'string' && newVal.length > 0) ||
            (typeof newVal === 'object' &&
                newVal &&
                Object.keys(newVal).length > 0)

        if (hasMessage) {
            visible.value = true
            startAutoClose()
        }
    },
    { immediate: true }
)

onBeforeUnmount(() => {
    clearTimer()
})
</script>

