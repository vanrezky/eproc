<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import Heading from '@/components/Heading.vue';
import Toast from '@/components/Toast.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { eproc_create, eproc_destroy, eproc_edit, eproc_index, eproc_show } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface Props {
    eprocs: {
        data: Array<{
            id: number;
            nama_perusahaan_rekanan: string;
            link_website: string | null;
            keterangan: string | null;
            created_at: string;
        }>;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        current_page: number;
        last_page: number;
        total: number;
        from: number | null;
        to: number | null;
    };
}

defineProps<Props>();

const page = usePage();
const { toast, close } = useToast();
const openDialog = ref<number | null>(null);

// Watch for flash messages
watch(
    () => page.props.flash,
    (flash) => {
        if (flash && (flash as { success?: string; error?: string }).success) {
            toast.value.show = true;
            toast.value.type = 'success';
            toast.value.title = 'Success';
            toast.value.message = (flash as { success?: string }).success || '';
            openDialog.value = null;
        } else if (flash && (flash as { error?: string }).error) {
            toast.value.show = true;
            toast.value.type = 'error';
            toast.value.title = 'Error';
            toast.value.message = (flash as { error?: string }).error || '';
            openDialog.value = null;
        }
    },
    { immediate: true },
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Eproc',
        href: eproc_index().url,
    },
];
</script>

<template>
    <Head title="Eproc" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <!-- Toast Notification -->
            <Toast :show="toast.show" :type="toast.type" :title="toast.title" :message="toast.message" @close="close" />

            <div class="flex items-center justify-between">
                <Heading variant="small" title="Eproc" description="Manage eproc data" />
                <Link :href="eproc_create().url">
                    <Button>Add Eproc</Button>
                </Link>
            </div>

            <div class="overflow-hidden rounded-md border">
                <table class="w-full text-sm">
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="p-3 text-left font-medium">Nama Perusahaan Rekanan</th>
                            <th class="p-3 text-left font-medium">Link Website</th>
                            <th class="p-3 text-left font-medium">Keterangan</th>
                            <th class="p-3 text-left font-medium">Created At</th>
                            <th class="p-3 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="eproc in eprocs.data" :key="eproc.id" class="border-b last:border-0 hover:bg-muted/25">
                            <td class="p-3">{{ eproc.nama_perusahaan_rekanan }}</td>
                            <td class="p-3">{{ eproc.link_website || '-' }}</td>
                            <td class="p-3">{{ eproc.keterangan || '-' }}</td>
                            <td class="p-3">{{ eproc.created_at }}</td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="eproc_show(eproc.id).url">
                                        <Button variant="outline" size="sm">View</Button>
                                    </Link>
                                    <Link :href="eproc_edit(eproc.id).url">
                                        <Button variant="outline" size="sm">Edit</Button>
                                    </Link>
                                    <Dialog :open="openDialog === eproc.id" @update:open="(val) => (openDialog = val ? eproc.id : null)">
                                        <DialogTrigger as-child>
                                            <Button variant="destructive" @click="openDialog = eproc.id">Delete</Button>
                                        </DialogTrigger>
                                        <DialogContent>
                                            <Form
                                                :action="eproc_destroy(eproc.id).url"
                                                method="post"
                                                class="space-y-6"
                                            >
                                                <input type="hidden" name="_method" value="delete" />
                                                <DialogHeader class="space-y-3">
                                                    <DialogTitle>Yakin hapus eproc ini?</DialogTitle>
                                                </DialogHeader>
                                                <DialogDescription />
                                                <DialogFooter class="gap-2">
                                                    <Button
                                                        type="button"
                                                        variant="secondary"
                                                        @click="openDialog = null"
                                                    >
                                                        Cancel
                                                    </Button>
                                                    <Button
                                                        type="submit"
                                                        variant="destructive"
                                                    >
                                                        Delete
                                                    </Button>
                                                </DialogFooter>
                                            </Form>
                                        </DialogContent>
                                    </Dialog>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="eprocs.last_page > 1" class="flex items-center justify-between border-t px-4 py-4">
                <div class="text-sm text-muted-foreground">Showing {{ eprocs.from }} to {{ eprocs.to }} of {{ eprocs.total }} results</div>
                <div class="flex gap-1">
                    <Link
                        v-for="link in eprocs.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        :class="[
                            'rounded-md px-3 py-1.5 text-sm transition-colors',
                            link.active ? 'bg-primary text-primary-foreground' : 'bg-secondary text-secondary-foreground hover:bg-muted',
                            !link.url && 'pointer-events-none opacity-50',
                        ]"
                        :disabled="!link.url"
                    >
                        {{ link.label }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
