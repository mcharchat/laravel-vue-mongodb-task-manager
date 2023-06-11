<script setup>
//view for one user showing (tasks) 
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import ProjectTask from '../Tasks/Partials/ProjectTask.vue';
import eventBus from '@/Utils/eventBus';
import DropdownLink from '@/Components/DropdownLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import { stringToColour } from '@/Utils/globalFunctions';

const user = usePage().props.user;

const usersTasks = usePage().props.usersTasks;
const usersProjectTasks = usePage().props.usersProjectTasks;
const assignedTasks = usePage().props.assignedTasks;
const assignedProjectTasks = usePage().props.assignedProjectTasks;
const teamTasks = usePage().props.teamTasks;
const teamProjectTasks = usePage().props.teamProjectTasks;
const projects = usePage().props.projects;

const activeTab = ref('projects');

const selectedTasks = ref(JSON.parse(localStorage.getItem('selectedTasks')));
const allTasks = [...usersTasks, ...assignedTasks, ...teamTasks, ...Object.values(usersProjectTasks), ...Object.values(assignedProjectTasks), ...Object.values(teamProjectTasks)].flat().filter((task, index, self) => index === self.findIndex((t) => t._id === task._id));

function displayMenuFunc() {
    return selectedTasks?.value.some((task) => allTasks.some((t) => t._id === task));
};

function selectedTasksInThisPage() {
    return selectedTasks?.value.filter((task) => allTasks.some((t) => t._id === task));
};

const displayMenu = ref(displayMenuFunc());

onMounted(() => {
    eventBus.$on('taskCheckbox', (content) => {
        selectedTasks.value = JSON.parse(localStorage.getItem('selectedTasks'))
        displayMenu.value = displayMenuFunc();
    });
});

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
                            activeTab === 'projects',
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-gray-50 dark:border-gray-950':
                            activeTab !== 'projects'
                    }"
                    @click="activeTab = 'projects'"
                >
                    User's Projects
                </div>
                <div
                    :class="{
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-indigo-400 dark:border-indigo-600 text-indigo-800 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out':
                            activeTab === 'usersTasks',
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-gray-50 dark:border-gray-950':
                            activeTab !== 'usersTasks'
                    }"
                    @click="activeTab = 'usersTasks'"
                >
                    User's Tasks
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
                <div
                    class="grow grid justify-end items-center"
                >

                    <div class="relative">
                        <Dropdown align="taskContext" width="48">
                            <template #trigger>
                                <div class="px-4"
                                    :class="{
                                        'text-gray-600 dark:text-gray-300 transition duration-150 ease-in-out': true,
                                        'opacity-0': !displayMenu,
                                        'opacity-100': displayMenu,
                                    }"
                                >
                                    <Icon icon="mdi:dots-vertical" class="cursor-pointer" v-tooltip="'More Options'"></Icon>
                                </div>
                            </template>

                            <template #content>
                                <DropdownLink href="#"> Profile </DropdownLink>
                                <DropdownLink href="#"> Log Out </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div v-if="activeTab === 'usersTasks'">
                    <h2 class="text-lg font-semibold mb-2">Free Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div class="p-2">
                            <ProjectTask :project="usersTasks"/>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mb-2">Project Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div v-for="(project, project_id) in usersProjectTasks" :key="project_id" class="p-2">
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
                <div v-else-if="activeTab === 'projects'">
                    <h2 class="text-lg font-semibold mb-2">Projects</h2>
                    <div class="flex flex-wrap gap-6 mb-2">
                        <h3 v-if="projects.length == 0" class="text-md font-bold grow text-center">No matching projects.</h3>
                        <div v-for="project in projects" :key="project._id" v-tooltip="project.name">
                            <Link class="inline-block border-4 rounded-full border-white text-white flex items-center justify-center text-lg font-bold cursor-pointer  dark:text-gray-800 hover:text-gray-100 dark:hover:text-gray-600 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out" :style="{ 'aspectRatio': '1/1', 'width': '48px', 'backgroundColor': stringToColour(project.name) }" :href="route('projects.show', project._id)">
                                {{ project.name.charAt(0) }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
