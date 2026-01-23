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
import { eproc_index, eproc_store } from '@/routes';
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
        title: 'Eproc',
        href: eproc_index().url,
    },
    {
        title: 'Create Eproc',
        href: eproc_store().url,
    },
];
</script>

<template>
    <Head title="Create Eproc" />

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
                title="Create Eproc"
                description="Add a new eproc data"
            />

            <Form
                :action="eproc_store().url"
                method="post"
                class="max-w-xl space-y-6"
            >
                <div class="grid gap-2">
                    <Label for="nama_perusahaan_rekanan">Nama Perusahaan Rekanan</Label>
                    <Input
                        id="nama_perusahaan_rekanan"
                        name="nama_perusahaan_rekanan"
                        type="text"
                        required
                        autocomplete="off"
                        placeholder="Nama perusahaan rekanan"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).nama_perusahaan_rekanan" class="mt-2" :message="(page.props.errors as Record<string, string>).nama_perusahaan_rekanan" />
                </div>

                <div class="grid gap-2">
                    <Label for="username">Username</Label>
                    <Input
                        id="username"
                        name="username"
                        type="text"
                        required
                        autocomplete="off"
                        placeholder="Username"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).username" class="mt-2" :message="(page.props.errors as Record<string, string>).username" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        name="password"
                        type="text"
                        required
                        autocomplete="off"
                        placeholder="Password"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).password" class="mt-2" :message="(page.props.errors as Record<string, string>).password" />
                </div>

                <div class="grid gap-2">
                    <Label for="link_website">Link Website</Label>
                    <Input
                        id="link_website"
                        name="link_website"
                        type="url"
                        autocomplete="off"
                        placeholder="https://example.com"
                    />
                    <InputError v-if="(page.props.errors as Record<string, string>).link_website" class="mt-2" :message="(page.props.errors as Record<string, string>).link_website" />
                </div>

                <div class="grid gap-2">
                    <Label for="keterangan">Keterangan</Label>
                    <textarea
                        id="keterangan"
                        name="keterangan"
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        autocomplete="off"
                        placeholder="Keterangan"
                        rows="4"
                    ></textarea>
                    <InputError v-if="(page.props.errors as Record<string, string>).keterangan" class="mt-2" :message="(page.props.errors as Record<string, string>).keterangan" />
                </div>

                <div class="flex items-center gap-4">
                    <Button type="submit">Create Eproc</Button>
                    <Link :href="eproc_index().url">
                        <Button variant="outline" type="button">Cancel</Button>
                    </Link>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
