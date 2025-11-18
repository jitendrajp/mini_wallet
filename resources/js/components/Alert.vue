<template>
    <div v-if="visible"
         :class="['flex items-center justify-between p-4 mb-4 text-sm rounded-lg transition-opacity duration-300', typeClasses]"
         role="alert">
        <div v-if="isStringMessage" class="flex items-center gap-2">
            <slot>{{ message }}</slot>
        </div>

        <div v-else-if="isObjectMessage" class="flex flex-col gap-1">
            <template v-for="(errors, field, i) in message" :key="field">
                <div v-for="(error, index) in errors" :key="index">{{ '• ' + error }}</div>
            </template>
        </div>

        <button v-if="showClose" class="ml-4 font-bold hover:opacity-75" @click="close">
            &times;
        </button>
    </div>
</template>

<script setup>
import {computed, onMounted, ref, watch} from 'vue';

const props = defineProps({
    type: {type: String, default: 'info'},
    message: {default: ''},
    duration: {type: Number, default: 5000},
    showClose: {type: Boolean, default: true},
});

const visible = ref(true);

const close = () => {
    visible.value = false;
};

const typeClasses = computed(() => {
    switch (props.type) {
        case 'success':
            return 'bg-green-100 text-green-700';
        case 'error':
            return 'bg-red-100 text-red-700';
        case 'warning':
            return 'bg-yellow-100 text-yellow-700';
        default:
            return 'bg-blue-100 text-blue-700';
    }
});

const isStringMessage = computed(() => typeof props.message === 'string' && props.message.length > 0);
const isObjectMessage = computed(() => typeof props.message === 'object' && props.message && Object.keys(props.message).length > 0);

onMounted(() => {
    if (props.duration > 0 && props.showClose) {
        setTimeout(() => close(), props.duration);
    }
});

watch(
    () => props.message,
    (newVal) => {
        if ((typeof newVal === 'string' && newVal.length > 0) ||
            (typeof newVal === 'object' && newVal && Object.keys(newVal).length > 0)) {
            visible.value = true;
        }
    }
);
</script>
