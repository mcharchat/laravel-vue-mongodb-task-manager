<script setup>
// view for users listing
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UsersTable from './Partials/UsersTable.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { useToast } from "vue-toastification";
import { defineProps, onMounted } from 'vue';


defineProps({
    users: Object,
});
onMounted(() => {
    const  redirectStatus = usePage().props.redirectStatus;
    const toast = useToast();
    if (redirectStatus.success) {
        toast.success(redirectStatus.success);
    }
    if (redirectStatus.error) {
        toast.error(redirectStatus.error);
    }
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
