<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import Toast from '@/components/Toast.vue';
import { Button } from '@/components/ui/button';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { eproc_edit, eproc_index } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface Props {
    eproc: {
        id: number;
        nama_perusahaan_rekanan: string;
        link_website: string | null;
        keterangan: string | null;
        created_at: string;
    };
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
</script>

<template>
    <Head title="View Eproc" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <!-- Toast Notification -->
            <Toast
                :show="toast.show"
                :type="toast.type"
                :title="toast.title"
                :message="toast.message"
                @close="close"
            />

            <Heading
                variant="small"
                title="View Eproc"
                description="Eproc details"
            />

            <div class="max-w-xl rounded-lg border p-6">
                <div class="space-y-4">
                    <div class="grid gap-2">
                        <Label class="text-muted-foreground text-sm">Nama Perusahaan Rekanan</Label>
                        <div class="font-medium">{{ eproc.nama_perusahaan_rekanan }}</div>
                    </div>

                    <div class="grid gap-2">
                        <Label class="text-muted-foreground text-sm">Link Website</Label>
                        <div class="font-medium">
                            <a v-if="eproc.link_website" :href="eproc.link_website" target="_blank" class="text-primary hover:underline">
                                {{ eproc.link_website }}
                            </a>
                            <span v-else>-</span>
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label class="text-muted-foreground text-sm">Keterangan</Label>
                        <div class="font-medium">{{ eproc.keterangan || '-' }}</div>
                    </div>

                    <div class="grid gap-2">
                        <Label class="text-muted-foreground text-sm">Created At</Label>
                        <div class="font-medium">{{ eproc.created_at }}</div>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <Link :href="eproc_edit(eproc.id).url">
                        <Button variant="outline" type="button">Edit</Button>
                    </Link>
                    <Link :href="eproc_index().url">
                        <Button variant="secondary" type="button">Back</Button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
