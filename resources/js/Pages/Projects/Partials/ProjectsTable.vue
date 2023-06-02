<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';
import { stringToColour } from '@/Utils/globalFunctions';

const props = defineProps({
    myProjects: Object,
    projects: Object,
});
const searchMyProjects = ref('');
const searchAllProjects = ref('');
const user = usePage().props.auth.user;

// general function that returns the filtered projects, based on the search input
function filterProjects(projects, search) {
    if (search === '') {
        return projects;
    }
    return projects.filter((project) => {
        return project.name.toLowerCase().includes(search.toLowerCase()) 
        || project.user.name.toLowerCase().includes(search.toLowerCase())
        || project.description.toLowerCase().includes(search.toLowerCase());
    });
}

// function that filters the projects based on the search input, using filterProjects function
const filteredMyProjects = computed(() => {
    return filterProjects(props.myProjects, searchMyProjects.value);
});

// function that filters the projects based on the search input, using filterProjects function
const filteredProjects = computed(() => {
    return filterProjects(props.projects, searchAllProjects.value);
});

// function that marks a project for a user as starred or not and send to the backend
function toggleStarred(project) {
    const starredProjects = user.starred_projects || [];
    // if starred_projects contains project id, remove the project from the array, otherwise add it
    if (starredProjects.includes(project._id)) {
        starredProjects.splice(starredProjects.indexOf(project._id), 1);
    } else {
        starredProjects.push(project._id);
    }
    axios.put(route('profile.starred-projects.update'), {
        starredProjects        
    });
}




</script>

<template>
        <section>
            <header class="mb-4 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">My Projects</h2>
                <div>
                    <InputLabel for="searchMyProjects" value="Search my projects" />
                    <TextInput
                        id="searchMyProjects"
                        type="text"
                        class="block"
                        v-model="searchMyProjects"
                        autocomplete="searchMyProjects"
                    />
                </div>
            </header>
            <div class="flex flex-wrap gap-4">
                <div v-for="project in filteredMyProjects" :key="project._id">
                    <div class="flex flex-col bg-white rounded-lg shadow p-4 justify-around" :style="{'aspectRatio': '1/1' , 'width': '155px'}">
                        <div class="flex justify-end items-center">
                            <svg v-if="!user.starred_projects?.includes(project._id)" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" class="cursor-pointer" @click="toggleStarred(project)"><path fill="currentColor" d="M237.3 97.9a13.78 13.78 0 0 0-12.08-9.6l-59.46-5.14a2 2 0 0 1-1.65-1.22l-23.23-55.36a14 14 0 0 0-25.76 0L91.89 81.94a2 2 0 0 1-1.65 1.22L30.78 88.3a13.78 13.78 0 0 0-12.08 9.6a14 14 0 0 0 4.11 15l45.11 39.35a2.06 2.06 0 0 1 .64 2L55 212.76a14 14 0 0 0 5.45 14.56a13.74 13.74 0 0 0 15.4.62l51.11-31a1.9 1.9 0 0 1 2 0l51.11 31A14 14 0 0 0 201 212.76l-13.52-58.53a2.06 2.06 0 0 1 .64-2l45.11-39.35a14 14 0 0 0 4.07-14.98Zm-12 5.92l-45.11 39.35a14 14 0 0 0-4.44 13.76l13.52 58.53a2 2 0 0 1-.79 2.13a1.81 1.81 0 0 1-2.14.09l-51.11-31a13.92 13.92 0 0 0-14.46 0l-51.11 31a1.81 1.81 0 0 1-2.14-.09a2 2 0 0 1-.79-2.13l13.52-58.53a14 14 0 0 0-4.44-13.76L30.7 103.82a2 2 0 0 1-.59-2.19a1.86 1.86 0 0 1 1.7-1.38l59.47-5.14A14 14 0 0 0 103 86.58l23.23-55.36a2 2 0 0 1 3.62 0L153 86.58a14 14 0 0 0 11.68 8.53l59.47 5.14a1.86 1.86 0 0 1 1.7 1.38a2 2 0 0 1-.55 2.19Z"/></svg>
                            <svg v-if="user.starred_projects?.includes(project._id)" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" class="cursor-pointer" @click="toggleStarred(project)"><path fill="yellow" d="m234.5 114.38l-45.1 39.36l13.51 58.6a16 16 0 0 1-23.84 17.34l-51.11-31l-51 31a16 16 0 0 1-23.84-17.34l13.49-58.54l-45.11-39.42a16 16 0 0 1 9.11-28.06l59.46-5.15l23.21-55.36a15.95 15.95 0 0 1 29.44 0L166 81.17l59.44 5.15a16 16 0 0 1 9.11 28.06Z"/></svg>
                        </div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-md font-bold" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap', 'width': '120px'}" v-tooltip="project.name">{{ project.name }}</h3>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="">
                                <div class="relative">
                                    <Dropdown align="bla" width="48">
                                        <template #trigger>
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actions</button>
                                        </template>
                                        <template #content>
                                            <DropdownLink :href="route('projects.show', project._id)"> Show </DropdownLink>
                                            <DropdownLink :href="route('projects.edit', project._id)"> Edit </DropdownLink>
                                            <DropdownLink :href="route('projects.destroy', project._id)" method="delete" as="button" class="text-left"> Delete </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>
                            <div class="inline-block rounded-full h-8 w-8 text-white flex items-center justify-center text-md font-bold" :style="{backgroundColor: stringToColour(project.user.name)}" v-tooltip="project.user.name">
                                {{ project.user.name.charAt(0) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="filteredMyProjects.length != 0">
                    <Link :href="route('projects.create')" class="text-blue-500 hover:text-blue-700 font-bold">
                        <div class="flex flex-col rounded-lg shadow p-4 justify-around border-dashed border-2 border-blue-500 hover:border-blue-700" :style="{ 'aspectRatio': '1/1', 'width': '155px' }">
                            <div class="flex items-center justify-center">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 28 28"><path fill="currentColor" d="M24 13h-9V4a1 1 0 1 0-2 0v9H4a1 1 0 1 0 0 2h9v9a1 1 0 1 0 2 0v-9h9a1 1 0 1 0 0-2Z"/></svg>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
                <h3 v-if="filteredMyProjects.length == 0" class="text-xl font-bold grow text-center">No matching projects, <Link :href="route('projects.create')" class="text-blue-500 hover:text-blue-700 ">create one</Link>!</h3>
            </div>
        </section>
        <section>
            <header class="mb-4 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">All the projects</h2>
                <div>
                    <InputLabel for="searchAllProjects" value="Search all projects" />
                    <TextInput
                        id="searchAllProjects"
                        type="text"
                        class="block"
                        v-model="searchAllProjects"
                        autocomplete="searchAllProjects"
                    />
                </div>
            </header>
            <div class="flex flex-wrap gap-4">
                <div v-for="project in filteredProjects" :key="project._id">
                    <div class="flex flex-col bg-white rounded-lg shadow p-4 justify-around" :style="{'aspectRatio': '1/1' , 'width': '155px'}">
                            <div class="flex justify-end items-center">
                                <svg v-if="!user.starred_projects?.includes(project._id)" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" class="cursor-pointer" @click="toggleStarred(project)"><path fill="currentColor" d="M237.3 97.9a13.78 13.78 0 0 0-12.08-9.6l-59.46-5.14a2 2 0 0 1-1.65-1.22l-23.23-55.36a14 14 0 0 0-25.76 0L91.89 81.94a2 2 0 0 1-1.65 1.22L30.78 88.3a13.78 13.78 0 0 0-12.08 9.6a14 14 0 0 0 4.11 15l45.11 39.35a2.06 2.06 0 0 1 .64 2L55 212.76a14 14 0 0 0 5.45 14.56a13.74 13.74 0 0 0 15.4.62l51.11-31a1.9 1.9 0 0 1 2 0l51.11 31A14 14 0 0 0 201 212.76l-13.52-58.53a2.06 2.06 0 0 1 .64-2l45.11-39.35a14 14 0 0 0 4.07-14.98Zm-12 5.92l-45.11 39.35a14 14 0 0 0-4.44 13.76l13.52 58.53a2 2 0 0 1-.79 2.13a1.81 1.81 0 0 1-2.14.09l-51.11-31a13.92 13.92 0 0 0-14.46 0l-51.11 31a1.81 1.81 0 0 1-2.14-.09a2 2 0 0 1-.79-2.13l13.52-58.53a14 14 0 0 0-4.44-13.76L30.7 103.82a2 2 0 0 1-.59-2.19a1.86 1.86 0 0 1 1.7-1.38l59.47-5.14A14 14 0 0 0 103 86.58l23.23-55.36a2 2 0 0 1 3.62 0L153 86.58a14 14 0 0 0 11.68 8.53l59.47 5.14a1.86 1.86 0 0 1 1.7 1.38a2 2 0 0 1-.55 2.19Z"/></svg>
                                <svg v-if="user.starred_projects?.includes(project._id)" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256" class="cursor-pointer" @click="toggleStarred(project)"><path fill="yellow" d="m234.5 114.38l-45.1 39.36l13.51 58.6a16 16 0 0 1-23.84 17.34l-51.11-31l-51 31a16 16 0 0 1-23.84-17.34l13.49-58.54l-45.11-39.42a16 16 0 0 1 9.11-28.06l59.46-5.15l23.21-55.36a15.95 15.95 0 0 1 29.44 0L166 81.17l59.44 5.15a16 16 0 0 1 9.11 28.06Z"/></svg>
                            </div>
                            <div class="flex justify-between items-center">
                                <h3 class="text-md font-bold" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap', 'width': '120px' }" v-tooltip="project.name">{{ project.name }}</h3>
                            </div>
                            <div class="flex items-center justify-between">
                            <div class="">
                                <div class="relative">
                                    <Dropdown align="bla" width="48">
                                        <template #trigger>
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actions</button>
                                        </template>
                                        <template #content>
                                            <DropdownLink :href="route('projects.show', project._id)"> Show </DropdownLink>
                                            <DropdownLink v-if="project.user._id == user._id" :href="route('projects.edit', project._id)"> Edit </DropdownLink>
                                            <DropdownLink v-if="project.user._id == user._id" :href="route('projects.destroy', project._id)" method="delete" as="button" class="text-left"> Delete </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>
                            <div class="inline-block rounded-full h-8 w-8 text-white flex items-center justify-center text-md font-bold" :style="{ backgroundColor: stringToColour(project.user.name) }" v-tooltip="project.user.name">
                                {{ project.user.name.charAt(0) }}
                            </div>
                        </div>
                    </div>
                </div>
                <h3 v-if="filteredProjects.length == 0" class="text-xl font-bold grow text-center">No matching projects</h3>
            </div>
        </section>
</template>
