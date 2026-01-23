<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <div><img src="../../../public/assets/logo.png" alt="logo" :class="className" v-bind="$attrs" /></div>
</template>
