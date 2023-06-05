<script setup>
//view for one user showing (tasks) 
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProjectTask from '../Tasks/Partials/ProjectTask.vue';

const user = usePage().props.user;

const myTasks = usePage().props.myTasks;
const myProjectTasks = usePage().props.myProjectTasks;
const assignedTasks = usePage().props.assignedTasks;
const assignedProjectTasks = usePage().props.assignedProjectTasks;
const teamTasks = usePage().props.teamTasks;
const teamProjectTasks = usePage().props.teamProjectTasks;

const activeTab = ref('myTasks');
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">User {{ user.name }}</h2>
        </template>

        <div class="bg-white dark:bg-gray-800 overflow-hidden">
            <div class="flex bg-gray-50 dark:bg-gray-950">
                <div
                    :class="{
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-indigo-400 dark:border-indigo-600 text-indigo-800 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out':
                            activeTab === 'myTasks',
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-gray-50 dark:border-gray-950':
                            activeTab !== 'myTasks'
                    }"
                    @click="activeTab = 'myTasks'"
                >
                    My Tasks
                </div>
                <div
                    :class="{
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-indigo-400 dark:border-indigo-600 text-indigo-800 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out':
                            activeTab === 'assignedTasks',
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-gray-50 dark:border-gray-950':
                            activeTab !== 'assignedTasks'
                    }"
                    @click="activeTab = 'assignedTasks'"
                >
                    Assigned Tasks
                </div>
                <div
                    :class="{
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-indigo-400 dark:border-indigo-600 text-indigo-800 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out':
                            activeTab === 'teamTasks',
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-gray-50 dark:border-gray-950':
                            activeTab !== 'teamTasks'
                    }"
                    @click="activeTab = 'teamTasks'"
                >
                    Team Tasks
                </div>
            </div>
            <div class="p-4">
                <div v-if="activeTab === 'myTasks'">
                    <h2 class="text-lg font-semibold mb-2">Free Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div class="p-2">
                            <ProjectTask :project="myTasks"/>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mb-2">Project Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div v-for="(project, project_id) in myProjectTasks" :key="project_id" class="p-2">
                            <ProjectTask :project="project"/>
                        </div>
                    </div>
                </div>
                <div v-else-if="activeTab === 'assignedTasks'">
                    <h2 class="text-lg font-semibold mb-2">Free Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div class="p-2">
                            <ProjectTask :project="assignedTasks"/>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mb-2">Project Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div v-for="(project, project_id) in assignedProjectTasks" :key="project_id" class="p-2">
                            <ProjectTask :project="project"/>
                        </div>
                    </div>
                </div>
                <div v-else-if="activeTab === 'teamTasks'">
                    <h2 class="text-lg font-semibold mb-2">Free Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div class="p-2">
                            <ProjectTask :project="teamTasks"/>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mb-2">Project Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div v-for="(project, project_id) in teamProjectTasks" :key="project_id" class="p-2">
                            <ProjectTask :project="project"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
