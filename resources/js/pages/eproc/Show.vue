<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import Toast from '@/components/Toast.vue';
import { Button } from '@/components/ui/button';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { eproc_edit, eproc_index } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface Log {
    id: number;
    action: string;
    user: {
        name: string;
    };
    old_data: Record<string, unknown> | null;
    new_data: Record<string, unknown> | null;
    created_at: string;
}

interface Props {
    eproc: {
        id: number;
        nama_perusahaan_rekanan: string;
        link_website: string | null;
        keterangan: string | null;
        username: string;
        password: string;
        user: {
            name: string;
        };
        created_at: string;
        updated_at: string | null;
        updated_user_name: string | null;
    };
    logs: Log[];
}

const props = defineProps<Props>();

const page = usePage();
const { toast, close } = useToast();

// Check for flash messages
const flash = page.props.flash as { success?: string; error?: string } | null;
if (flash?.success) {
    toast.value.show = true;
    toast.value.type = 'success';
    toast.value.title = 'Success';
    toast.value.message = flash.success;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Eproc',
        href: eproc_index().url,
    },
    {
        title: 'View Eproc',
        href: eproc_edit(props.eproc.id).url,
    },
];

const formatDate = (dateString: string | null) => {
    if (!dateString) return '-';

    const date = new Date(dateString);
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day} ${month} ${year}, ${hours}:${minutes}`;
};

const getActionLabel = (action: string) => {
    const labels: Record<string, string> = {
        create: 'Dibuat',
        update: 'Diperbarui',
        delete: 'Dihapus',
    };
    return labels[action] || action;
};

const getActionColor = (action: string) => {
    const colors: Record<string, string> = {
        create: 'bg-green-100 text-green-800',
        update: 'bg-blue-100 text-blue-800',
        delete: 'bg-red-100 text-red-800',
    };
    return colors[action] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="View Eproc" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <!-- Toast Notification -->
            <Toast :show="toast.show" :type="toast.type" :title="toast.title" :message="toast.message" @close="close" />

            <Heading variant="small" title="View Eproc" description="Eproc details" />

            <!-- Eproc Details Card -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl leading-none font-semibold tracking-tight">Detail Eproc</h3>
                    <p class="text-sm text-muted-foreground">Informasi lengkap data eproc</p>
                </div>
                <div class="p-6 pt-0">
                    <!-- Header Section: Nama Perusahaan + Updated Date -->
                    <div class="mb-4 grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
                        <div class="grid gap-4">
                            <label class="font-bold">Nama Perusahaan</label>
                            <div>{{ eproc.nama_perusahaan_rekanan }}</div>
                        </div>
                        <div class="grid gap-4 text-sm">
                            <label class="text-muted-foreground">Diperbarui: </label>
                            <div class="text-sky-400">
                                {{ formatDate(eproc.updated_at) }} <span class="font-medium text-sky-400">by {{ eproc.updated_user_name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid gap-4">
                        <label class="font-bold text-muted-foreground">Link Website</label>
                        <div class="">
                            <a v-if="eproc.link_website" :href="eproc.link_website" target="_blank" class="text-primary hover:underline">
                                {{ eproc.link_website }}
                            </a>
                            <span v-else>-</span>
                        </div>

                        <div class="flex md:gap-56">
                            <div class="flex flex-col gap-5">
                                <label class="font-bold text-muted-foreground">User Id</label>
                                <div>{{ eproc.username }}</div>
                            </div>
                            <div class="flex flex-col gap-5">
                                <label class="font-bold text-muted-foreground">Password</label>
                                <div>{{ eproc.password }}</div>
                            </div>
                        </div>

                        <label class="text-sm font-medium text-muted-foreground">Keterangan</label>
                        <div class="min-h-28 rounded-xl border p-2 font-medium">{{ eproc.keterangan || '-' }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-6 pt-0">
                    <Link :href="eproc_edit(eproc.id).url">
                        <Button variant="outline" type="button">Edit</Button>
                    </Link>
                    <Link :href="eproc_index().url">
                        <Button variant="secondary" type="button">Back</Button>
                    </Link>
                </div>
            </div>

            <!-- Activity Logs Section -->
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl leading-none font-semibold tracking-tight">Activity Logs</h3>
                    <p class="text-sm text-muted-foreground">Riwayat perubahan data</p>
                </div>
                <div class="p-6 pt-0">
                    <div v-if="logs.length === 0" class="py-4 text-center text-muted-foreground">Belum ada aktivitas tercatat.</div>

                    <div class="space-y-4">
                        <div v-for="log in logs" :key="log.id" class="rounded-lg border p-4">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-2">
                                    <span :class="getActionColor(log.action)" class="rounded px-2 py-1 text-xs font-medium whitespace-nowrap">
                                        {{ getActionLabel(log.action) }}
                                    </span>
                                    <span class="text-sm text-muted-foreground">oleh {{ log.user?.name || 'Unknown' }}</span>
                                </div>
                                <span class="text-right text-xs text-muted-foreground">{{ formatDate(log.created_at) }}</span>
                            </div>

                            <div class="mt-3">
                                <div class="grid gap-2 text-sm">
                                    <div class="hidden gap-2 md:grid md:grid-cols-3">
                                        <span class="text-muted-foreground">Field</span>
                                        <span class="text-muted-foreground">Lama</span>
                                        <span class="text-muted-foreground">Baru</span>
                                    </div>
                                    <template v-for="(value, key) in log.new_data" :key="key">
                                        <template
                                            v-if="
                                                key !== 'id' &&
                                                key !== 'user_id' &&
                                                key !== 'created_at' &&
                                                key !== 'updated_date'
                                            "
                                        >
                                            <div class="grid grid-cols-1 gap-2 md:grid-cols-3">
                                                <span class="font-medium capitalize">{{ key.replace(/_/g, ' ') }}</span>
                                                <span class="break-all text-red-500">{{ String(  key === 'updated_at' ? formatDate(log.old_data?.[key]) : log.old_data?.[key] ?? '-') }}</span>
                                                <span class="break-all text-green-500">{{ key === 'updated_at' ? formatDate(value) : String(value ?? '-') }}</span>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>

                            <!-- <div v-else-if="log.action === 'create' && log.new_data" class="mt-3">
                                <div class="text-sm">
                                    <div class="mb-2 text-muted-foreground">Data yang dibuat:</div>
                                    <pre class="overflow-x-auto rounded bg-muted p-2 text-xs break-all">{{
                                        JSON.stringify(log.new_data, null, 2)
                                    }}</pre>
                                </div>
                            </div>

                            <div v-else-if="log.action === 'delete' && log.old_data" class="mt-3">
                                <div class="text-sm">
                                    <div class="mb-2 text-muted-foreground">Data yang dihapus:</div>
                                    <pre class="overflow-x-auto rounded bg-muted p-2 text-xs break-all">{{
                                        JSON.stringify(log.old_data, null, 2)
                                    }}</pre>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
