<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import ProjectTask from './Partials/ProjectTask.vue';
import eventBus from '@/Utils/eventBus';
import DropdownLink from '@/Components/DropdownLink.vue';
import Dropdown from '@/Components/Dropdown.vue';

const myTasks = usePage().props.myTasks;
const myProjectTasks = usePage().props.myProjectTasks;
const assignedTasks = usePage().props.assignedTasks;
const assignedProjectTasks = usePage().props.assignedProjectTasks;
const teamTasks = usePage().props.teamTasks;
const teamProjectTasks = usePage().props.teamProjectTasks;

const activeTab = ref('myTasks');

const selectedTasks = ref([]);
const allTasks = [...myTasks, ...assignedTasks, ...teamTasks, ...Object.values(myProjectTasks), ...Object.values(assignedProjectTasks), ...Object.values(teamProjectTasks)].flat().filter((task, index, self) => index === self.findIndex((t) => t._id === task._id));

function displayMenuFunc() {
    return selectedTasks?.value.some((task) => allTasks.some((t) => t._id === task));
};

function selectedTasksInThisPage() {
    return selectedTasks?.value.filter((task) => allTasks.some((t) => t._id === task));
};

const displayMenu = ref(displayMenuFunc());

onMounted(() => {
    eventBus.$on('taskCheckbox', (content) => {
        //check if content is in selectedTasks if not add it if yes remove it
        if (selectedTasks.value.includes(content)) {
            selectedTasks.value = selectedTasks.value.filter((task) => task !== content);
        } else {
            selectedTasks.value = [...selectedTasks.value, content];
        }
        displayMenu.value = displayMenuFunc();
    });
});

</script>

<template>
    <Head title="Task" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tasks</h2>
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
                <div v-if="activeTab === 'myTasks'">
                    <h2 class="text-lg font-semibold mb-2">Free Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div class="p-2">
                            <ProjectTask :project="myTasks" :selectedTasks="selectedTasks"/>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mb-2">Project Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div v-for="(project, project_id) in myProjectTasks" :key="project_id" class="p-2">
                            <ProjectTask :project="project" :selectedTasks="selectedTasks"/>
                        </div>
                    </div>
                </div>
                <div v-else-if="activeTab === 'assignedTasks'">
                    <h2 class="text-lg font-semibold mb-2">Free Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div class="p-2">
                            <ProjectTask :project="assignedTasks" :selectedTasks="selectedTasks"/>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mb-2">Project Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div v-for="(project, project_id) in assignedProjectTasks" :key="project_id" class="p-2">
                            <ProjectTask :project="project" :selectedTasks="selectedTasks"/>
                        </div>
                    </div>
                </div>
                <div v-else-if="activeTab === 'teamTasks'">
                    <h2 class="text-lg font-semibold mb-2">Free Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div class="p-2">
                            <ProjectTask :project="teamTasks" :selectedTasks="selectedTasks"/>
                        </div>
                    </div>
                    <h2 class="text-lg font-semibold mb-2">Project Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div v-for="(project, project_id) in teamProjectTasks" :key="project_id" class="p-2">
                            <ProjectTask :project="project" :selectedTasks="selectedTasks"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
