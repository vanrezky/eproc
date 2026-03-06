<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, eproc_show } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface Props {
    eprocs: Array<{
        id: number;
        nama_perusahaan_rekanan: string;
        keterangan: string | null;
        created_at: string;
        updated_at: string | null;
        updated_user: {
            name: string;
        } | null;
    }>;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
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
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="overflow-hidden rounded-md border overflow-x-auto">
                <div class="border-b bg-muted/50 p-3">
                    <h2 class="font-semibold">Eproc Terbaru</h2>
                </div>
                <table class="w-full text-sm">
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="p-3 text-left font-medium">Nama Eproc</th>
                            <th class="p-3 text-left font-medium">Keterangan</th>
                            <th class="p-3 text-left font-medium">Updated Date</th>
                            <th class="p-3 text-left font-medium">Updated User</th>
                            <th class="p-3 text-right font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="eproc in eprocs" :key="eproc.id" class="border-b last:border-0 hover:bg-muted/25">
                            <td class="p-3">{{ eproc.nama_perusahaan_rekanan }}</td>
                            <td class="p-3">{{ eproc.keterangan || '-' }}</td>
                            <td class="p-3">{{ formatDate(eproc.updated_at) }}</td>
                            <td class="p-3">{{ eproc.updated_user?.name || '-' }}</td>
                            <td class="p-3 text-right">
                                <Link :href="eproc_show(eproc.id).url">
                                    <Button variant="outline" size="sm">View</Button>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
