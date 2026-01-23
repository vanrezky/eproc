<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { user_edit, user_index, user_show } from '@/routes';
import { type BreadcrumbItem } from '@/types';

interface Props {
    user: {
        id: number;
        name: string;
        email: string;
        created_at: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: user_index().url,
    },
    {
        title: 'User Details',
        href: user_show(props.user.id).url,
    },
];
</script>

<template>
    <Head title="User Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <Heading
                variant="small"
                title="User Details"
                description="View user information"
            />

            <div class="rounded-md border p-4">
                <dl class="space-y-4">
                    <div class="grid grid-cols-3 gap-4 border-b pb-4">
                        <dt class="font-medium">ID</dt>
                        <dd class="col-span-2">{{ user.id }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 border-b pb-4">
                        <dt class="font-medium">Name</dt>
                        <dd class="col-span-2">{{ user.name }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 border-b pb-4">
                        <dt class="font-medium">Email</dt>
                        <dd class="col-span-2">{{ user.email }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <dt class="font-medium">Created At</dt>
                        <dd class="col-span-2">{{ user.created_at }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex gap-4">
                <Link :href="user_edit(user.id).url">
                    <Button variant="outline">Edit User</Button>
                </Link>
                <Link :href="user_index().url">
                    <Button variant="outline">Back to Users</Button>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
