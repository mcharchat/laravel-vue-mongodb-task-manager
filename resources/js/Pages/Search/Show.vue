<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import eventBus from '@/Utils/eventBus';
import DropdownLink from '@/Components/DropdownLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import ProjectTask from '../Tasks/Partials/ProjectTask.vue';
import { stringToColour } from '@/Utils/globalFunctions';

const searchedUsers = usePage().props.searchedUsers;
const searchedProjects = usePage().props.searchedProjects;
const searchedTasks = usePage().props.searchedTasks;
const activeTab = ref('searchedTerm');
const params = new URLSearchParams(window.location.search)
const searchedTerm = params.get('search')

const selectedTasks = ref(JSON.parse(localStorage.getItem('selectedTasks')));
const allTasks = [...Object.values(searchedTasks)].flat().filter((task, index, self) => index === self.findIndex((t) => t._id === task._id));

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
    <Head title="Task" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Search</h2>
        </template>

        <div class="bg-white dark:bg-gray-800 overflow-hidden">
            <div class="flex bg-gray-50 dark:bg-gray-950">
                <div
                    :class="{
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-indigo-400 dark:border-indigo-600 text-indigo-800 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out':
                            activeTab === 'searchedTerm',
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-gray-50 dark:border-gray-950':
                            activeTab !== 'searchedTerm'
                    }"
                    @click="activeTab = 'searchedTerm'"
                >
                    Searched term "{{ searchedTerm }}"
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
                <div v-if="activeTab === 'searchedTerm'">
                    <h2 class="text-lg font-semibold mb-2">Users</h2>
                    <div class="flex flex-wrap gap-4 mb-2">
                        <h3 v-if="searchedUsers.length == 0" class="text-md font-bold grow text-center">No matching users.</h3>
                        <div v-for="user in searchedUsers" :key="user._id" v-tooltip="user.name">
                            <Link class="inline-block rounded-full text-white flex items-center justify-center text-lg font-bold cursor-pointer" :style="{ 'aspectRatio': '1/1', 'width': '32px', 'backgroundColor': stringToColour(user.name) }" :href="route('users.show', user._id)">
                                {{ user.name.charAt(0) }}
                            </Link>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mb-2">Projects</h2>
                    <div class="flex flex-wrap gap-4 mb-2">
                        <h3 v-if="searchedProjects.length == 0" class="text-md font-bold grow text-center">No matching projects, <Link :href="route('projects.create')" class="text-blue-500 hover:text-blue-700 ">create one</Link>!</h3>
                        <div v-for="project in searchedProjects" :key="project._id" v-tooltip="project.name">
                            <Link class="inline-block rounded-full text-white flex items-center justify-center text-lg font-bold cursor-pointer" :style="{ 'aspectRatio': '1/1', 'width': '32px', 'backgroundColor': stringToColour(project.name) }" :href="route('projects.show', project._id)">
                                {{ project.name.charAt(0) }}
                            </Link>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mb-2">Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div v-for="(project, project_id) in searchedTasks" :key="project_id" class="p-2">
                            <ProjectTask :project="project"/>
                        </div>
                        <h3 v-if="searchedTasks.length == 0" class="text-md font-bold grow text-center">No matching tasks, <Link :href="route('tasks.create')" class="text-blue-500 hover:text-blue-700 ">create one</Link>!</h3>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>