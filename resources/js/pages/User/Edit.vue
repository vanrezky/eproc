<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import Toast from '@/components/Toast.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { user_index, user_update } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import Select from '@/components/ui/select/Select.vue';

interface Props {
    user: {
        id: number;
        name: string;
        email: string;
        role: string;
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
        title: 'Users',
        href: user_index().url,
    },
    {
        title: 'Edit User',
        href: user_update.put(props.user.id).url,
    },
];
</script>

<template>
    <Head title="Edit User" />

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
                title="Edit User"
                description="Update user information"
            />

            <Form
                :action="user_update.put(user.id).url"
                method="post"
                class="max-w-xl space-y-6"
            >
                <input type="hidden" name="_method" value="put" />

                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        :default-value="user.name"
                        required
                        autocomplete="name"
                        placeholder="Full name"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).name" class="mt-2" :message="(page.props.errors as Record<string, string>).name" />
                </div>
                <div class="grid gap-2">
                    <Label for="role">Role</Label>
                    <Select id="role" name="role" required autocomplete="role" placeholder="Select role" :default-value="user.role">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </Select>
                    <InputError v-if="(page.props.errors as Record<string, string>).role" class="mt-2" :message="(page.props.errors as Record<string, string>).role" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        name="email"
                        type="email"
                        :default-value="user.email"
                        required
                        autocomplete="email"
                        placeholder="Email address"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).email" class="mt-2" :message="(page.props.errors as Record<string, string>).email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">New Password (leave blank to keep current)</Label>
                    <Input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        placeholder="New password"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).password" class="mt-2" :message="(page.props.errors as Record<string, string>).password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm New Password</Label>
                    <Input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        placeholder="Confirm new password"
                    />
                </div>

                <div class="flex items-center gap-4">
                    <Button type="submit">Update User</Button>
                    <Link :href="user_index().url">
                        <Button variant="outline" type="button">Cancel</Button>
                    </Link>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
