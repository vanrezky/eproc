<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

import UserController from '@/actions/App/Http/Controllers/UserController';
import Heading from '@/components/Heading.vue';
import Toast from '@/components/Toast.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { user_create, user_edit, user_index, user_show } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface Props {
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            role: string;
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
            openDialog.value = null; // Close dialog on success
        } else if (flash && (flash as { error?: string }).error) {
            toast.value.show = true;
            toast.value.type = 'error';
            toast.value.title = 'Error';
            toast.value.message = (flash as { error?: string }).error || '';
            openDialog.value = null; // Close dialog on error
        }
    },
    { immediate: true },
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: user_index().url,
    },
];
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <!-- Toast Notification -->
            <Toast :show="toast.show" :type="toast.type" :title="toast.title" :message="toast.message" @close="close" />

            <div class="flex items-center justify-between">
                <Heading variant="small" title="Users" description="Manage system users" />
                <Link :href="user_create().url">
                    <Button>Add User</Button>
                </Link>
            </div>

            <div class="overflow-hidden rounded-md border overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="p-3 text-left font-medium">Name</th>
                            <th class="p-3 text-left font-medium">Email</th>
                            <th class="p-3 text-left font-medium">Role</th>
                            <th class="p-3 text-left font-medium">Created At</th>
                            <th class="p-3 text-right font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data" :key="user.id" class="border-b last:border-0 hover:bg-muted/25">
                            <td class="p-3">{{ user.name }}</td>
                            <td class="p-3">{{ user.email }}</td>
                            <td class="p-3">{{ user.role }}</td>
                            <td class="p-3">{{ user.created_at }}</td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="user_show(user.id).url">
                                        <Button variant="outline" size="sm">View</Button>
                                    </Link>
                                    <Link :href="user_edit(user.id).url">
                                        <Button variant="outline" size="sm">Edit</Button>
                                    </Link>
                                    <Dialog :open="openDialog === user.id" @update:open="(val) => (openDialog = val ? user.id : null)">
                                        <DialogTrigger as-child>
                                            <Button variant="destructive" data-test="delete-user-button" @click="openDialog = user.id">Delete</Button>
                                        </DialogTrigger>
                                        <DialogContent>
                                            <Form
                                                v-bind="UserController.destroy.form(user.id)"
                                                reset-on-success
                                                :options="{
                                                    preserveScroll: true,
                                                }"
                                                class="space-y-6"
                                                v-slot="{ processing, reset }"
                                            >
                                                <DialogHeader class="space-y-3">
                                                    <DialogTitle>Yakin hapus user ini?</DialogTitle>
                                                </DialogHeader>
                                                <DialogDescription />

                                                <DialogFooter class="gap-2">
                                                    <DialogClose as-child>
                                                        <Button
                                                            variant="secondary"
                                                            @click="
                                                                () => {
                                                                    reset();
                                                                }
                                                            "
                                                        >
                                                            Cancel
                                                        </Button>
                                                    </DialogClose>

                                                    <Button
                                                        type="submit"
                                                        variant="destructive"
                                                        :disabled="processing"
                                                        data-test="confirm-delete-user-button"
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
            <div v-if="users.last_page > 1" class="flex items-center justify-between border-t px-4 py-4">
                <div class="text-sm text-muted-foreground">Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results</div>
                <div class="flex gap-1">
                    <Link
                        v-for="link in users.links"
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
