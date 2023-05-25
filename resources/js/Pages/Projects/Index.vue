<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProjectsTable from './Partials/ProjectsTable.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    myProjects: Object,
    projects: Object,
});
</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Projects</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 gap-12 flex flex-col">
                <ProjectsTable 
                    :my-projects="myProjects"
                    :projects="projects"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
