<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Search, Building2, User, Loader2 } from 'lucide-vue-next';
import { useTextareaAutosize, useDebounceFn, onClickOutside } from '@vueuse/core';
import axios from 'axios';

interface SearchResult {
    id: number;
    title: string;
    subtitle: string;
    type: 'eproc' | 'user';
    url: string;
}

const query = ref('');
const results = ref<SearchResult[]>([]);
const isSearching = ref(false);
const showResults = ref(false);
const containerRef = ref<HTMLElement | null>(null);

const debouncedSearch = useDebounceFn(async (q: string) => {
    if (q.length < 2) {
        results.value = [];
        isSearching.value = false;
        return;
    }

    try {
        const response = await axios.get('/api/search', { params: { q } });
        results.value = response.data;
    } catch (error) {
        console.error('Search failed:', error);
    } finally {
        isSearching.value = false;
    }
}, 300);

watch(query, (newQuery) => {
    if (newQuery.length >= 2) {
        isSearching.value = true;
        showResults.value = true;
        debouncedSearch(newQuery);
    } else {
        results.value = [];
        showResults.value = false;
    }
});

onClickOutside(containerRef, () => {
    showResults.value = false;
});

const handleSelect = (url: string) => {
    showResults.value = false;
    query.value = '';
    router.visit(url);
};
</script>

<template>
    <div ref="containerRef" class="relative w-full max-w-sm">
        <div class="relative">
            <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
            <input
                v-model="query"
                type="text"
                placeholder="Search..."
                class="h-9 w-full rounded-md border border-input bg-muted/50 px-9 py-2 text-sm ring-offset-background transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                @focus="showResults = query.length >= 2"
            />
            <div v-if="isSearching" class="absolute right-3 top-1/2 -translate-y-1/2">
                <Loader2 class="h-4 w-4 animate-spin text-muted-foreground" />
            </div>
        </div>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-if="showResults && (results.length > 0 || !isSearching)"
                class="absolute z-50 mt-2 w-full overflow-hidden rounded-md border bg-popover text-popover-foreground shadow-md outline-none"
            >
                <div v-if="results.length > 0" class="p-1">
                    <button
                        v-for="result in results"
                        :key="`${result.type}-${result.id}`"
                        class="relative flex w-full cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50"
                        @click="handleSelect(result.url)"
                    >
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-md bg-muted">
                            <Building2 v-if="result.type === 'eproc'" class="h-4 w-4 text-primary" />
                            <User v-else class="h-4 w-4 text-primary" />
                        </div>
                        <div class="flex flex-col items-start overflow-hidden">
                            <span class="truncate font-medium">{{ result.title }}</span>
                            <span class="truncate text-xs text-muted-foreground">{{ result.subtitle }}</span>
                        </div>
                    </button>
                </div>
                <div v-else-if="query.length >= 2 && !isSearching" class="p-4 text-center text-sm text-muted-foreground">
                    No results found for "{{ query }}"
                </div>
            </div>
        </transition>
    </div>
</template>
