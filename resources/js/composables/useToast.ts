import { ref } from 'vue';

type ToastType = 'success' | 'error' | 'warning' | 'info';

interface ToastState {
    show: boolean;
    type: ToastType;
    title: string;
    message: string;
}

const toastState = ref<ToastState>({
    show: false,
    type: 'info',
    title: '',
    message: '',
});

let timeoutId: ReturnType<typeof setTimeout> | null = null;

export function useToast() {
    function show(type: ToastType, title: string, message: string = '', duration: number = 5000) {
        toastState.value = {
            show: true,
            type,
            title,
            message,
        };

        if (timeoutId) {
            clearTimeout(timeoutId);
        }

        timeoutId = setTimeout(() => {
            toastState.value.show = false;
        }, duration);
    }

    function success(title: string, message: string = '') {
        show('success', title, message);
    }

    function error(title: string, message: string = '') {
        show('error', title, message);
    }

    function warning(title: string, message: string = '') {
        show('warning', title, message);
    }

    function info(title: string, message: string = '') {
        show('info', title, message);
    }

    function close() {
        toastState.value.show = false;
        if (timeoutId) {
            clearTimeout(timeoutId);
            timeoutId = null;
        }
    }

    return {
        toast: toastState,
        show,
        success,
        error,
        warning,
        info,
        close,
    };
}
