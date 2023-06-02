<script setup>
// view for users listing
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UsersTable from './Partials/UsersTable.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    users: Object,
});
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Users</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 gap-12 flex flex-col">
                <UsersTable 
                    :users="users"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
