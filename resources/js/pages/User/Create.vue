<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import Toast from '@/components/Toast.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select } from '@/components/ui/select';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { user_index, user_store } from '@/routes';
import { type BreadcrumbItem } from '@/types';

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
        title: 'Create User',
        href: user_store().url,
    },
];
</script>

<template>
    <Head title="Create User" />

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
                title="Create User"
                description="Add a new user to the system"
            />

            <Form
                :action="user_store().url"
                method="post"
                class="max-w-xl space-y-6"
            >
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        required
                        autocomplete="name"
                        placeholder="Full name"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).name" class="mt-2" :message="(page.props.errors as Record<string, string>).name" />
                </div>
                 <div class="grid gap-2">
                    <Label for="role">Role</Label>
                    <Select id="role" name="role" required placeholder="Select role" :default-value="'user'">
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
                        required
                        autocomplete="email"
                        placeholder="Email address"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).email" class="mt-2" :message="(page.props.errors as Record<string, string>).email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="Password"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).password" class="mt-2" :message="(page.props.errors as Record<string, string>).password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm Password</Label>
                    <Input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm password"
                    />
                </div>

                <div class="flex items-center gap-4">
                    <Button type="submit">Create User</Button>
                    <Link :href="user_index().url">
                        <Button variant="outline" type="button">Cancel</Button>
                    </Link>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
