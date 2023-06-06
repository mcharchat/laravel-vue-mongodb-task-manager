<script setup>
//view for one project showing (tasks) 
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { ref , onMounted} from 'vue';
import { Icon } from '@iconify/vue';
import ProjectTask from '../Tasks/Partials/ProjectTask.vue';
import eventBus from '@/Utils/eventBus';
import DropdownLink from '@/Components/DropdownLink.vue';
import Dropdown from '@/Components/Dropdown.vue';

const project = usePage().props.project;

const activeTab = ref('projectTasks');

const selectedTasks = ref(JSON.parse(localStorage.getItem('selectedTasks')));
const allTasks = [...project.tasks].flat().filter((task, index, self) => index === self.findIndex((t) => t._id === task._id));

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
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Project {{ project.name }}</h2>
        </template>
        
        <div class="bg-white dark:bg-gray-800 overflow-hidden">
            <div class="flex bg-gray-50 dark:bg-gray-950">
                <div
                    :class="{
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-indigo-400 dark:border-indigo-600 text-indigo-800 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out':
                            activeTab === 'projectTasks',
                        'cursor-pointer text-base font-medium p-2 text-center text-gray-600 border-b-2 border-gray-50 dark:border-gray-950':
                            activeTab !== 'projectTasks'
                    }"
                    @click="activeTab = 'projectTasks'"
                >
                    Project Tasks
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
                <div v-if="activeTab === 'projectTasks'">
                    <h2 class="text-lg font-semibold mb-2">Tasks</h2>
                    <div class="flex flex-col gap-2">
                        <div class="p-2">
                            <ProjectTask :project="project.tasks"/>
                        </div>
                    </div>
                </div>
                <div v-else-if="activeTab === 'other'">
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
