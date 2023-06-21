<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TaskSummary from '@/Pages/Dashboard/Partials/TaskSummary.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const props = defineProps({
    status: String,
});

const $page = usePage();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-6">
            <div class="mx-auto px-6 lg:px-8 gap-6 grid">
                <div>
                    <div class="text-lg font-medium text-gray-600">
                        Welcome back, {{ $page.props.auth.user.name }}!
                    </div>
                    <div class="text-base font-light text-gray-500">
                        Squad ID: {{ $page.props.auth.user.squad_id }}
                    </div>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <TaskSummary
                        icon="fluent:task-list-square-add-20-regular"
                        title="My tasks"
                        color="#60a5fa"
                        :completed="0"
                        :pending="0" 
                    />
                    <TaskSummary
                        icon="fluent:task-list-square-person-20-regular"
                        title="Assigned tasks"
                        color="#f87171"
                        :completed="0"
                        :pending="0"
                    />
                    <TaskSummary
                        icon="fluent:task-list-square-database-20-regular"
                        title="Team tasks"
                        color="#34d399"
                        :completed="0"
                        :pending="0"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
