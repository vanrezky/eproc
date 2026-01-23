<script setup lang="ts">
import { CheckCircle, Info, AlertTriangle, XCircle } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    show?: boolean;
    type?: 'success' | 'error' | 'warning' | 'info';
    title?: string;
    message?: string;
}

const props = withDefaults(defineProps<Props>(), {
    show: false,
    type: 'info',
});

const emit = defineEmits<{
    close: [];
}>();

const icon = computed(() => {
    switch (props.type) {
        case 'success':
            return CheckCircle;
        case 'error':
            return XCircle;
        case 'warning':
            return AlertTriangle;
        default:
            return Info;
    }
});

const bgColor = computed(() => {
    switch (props.type) {
        case 'success':
            return 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-200';
        case 'error':
            return 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200';
        case 'warning':
            return 'bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-200';
        default:
            return 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-200';
    }
});

const iconColor = computed(() => {
    switch (props.type) {
        case 'success':
            return 'text-green-500 dark:text-green-400';
        case 'error':
            return 'text-red-500 dark:text-red-400';
        case 'warning':
            return 'text-yellow-500 dark:text-yellow-400';
        default:
            return 'text-blue-500 dark:text-blue-400';
    }
});
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="transform translate-x-full opacity-0"
        enter-to-class="transform translate-x-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="transform translate-x-0 opacity-100"
        leave-to-class="transform translate-x-full opacity-0"
    >
        <div
            v-if="show"
            :class="['flex items-start gap-3 rounded-lg border p-4 shadow-lg', bgColor]"
        >
            <component :is="icon" :class="['h-5 w-5 flex-shrink-0', iconColor]" />
            <div class="flex-1">
                <p v-if="title" class="font-medium">{{ title }}</p>
                <p v-if="message" class="text-sm">{{ message }}</p>
            </div>
            <button
                @click="emit('close')"
                class="flex-shrink-0 rounded-lg p-1 hover:bg-black/5 dark:hover:bg-white/5"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </Transition>
</template>
