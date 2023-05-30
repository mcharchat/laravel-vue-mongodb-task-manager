<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    myProjects: Object,
    projects: Object,
});
const searchMyProjects = ref('');
const searchAllProjects = ref('');

//function that generates a random color for the user avatar, based on the name
function stringToColour(str) {
    var hash = 0;
    for (var i = 0; i < str.length; i++) {
       hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    var colour = '#';
    for (var i = 0; i < 3; i++) {
       var value = (hash >> (i * 8)) & 0xFF;
       colour += ('00' + value.toString(16)).substr(-2);
    }
    return colour;
}

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
                        <h3 class="text-xl font-bold" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap' }" v-tooltip="project.name">{{ project.name }}</h3>
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
                            <h3 class="text-xl font-bold" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap' }" v-tooltip="project.name">{{ project.name }}</h3>
                                <div class="flex items-center justify-between">
                                <div class="">
                                    <div class="relative">
                                        <Dropdown align="bla" width="48">
                                            <template #trigger>
                                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actions</button>
                                            </template>
                                            <template #content>
                                                <DropdownLink :href="route('projects.show', project._id)"> Show </DropdownLink>
                                                <DropdownLink v-if="project.user._id == $page.props.auth.user._id" :href="route('projects.edit', project._id)"> Edit </DropdownLink>
                                                <DropdownLink v-if="project.user._id == $page.props.auth.user._id" :href="route('projects.destroy', project._id)" method="delete" as="button" class="text-left"> Delete </DropdownLink>
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
