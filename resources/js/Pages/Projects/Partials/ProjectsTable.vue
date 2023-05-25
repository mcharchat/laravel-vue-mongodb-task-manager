<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
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
            <div class="flex flex-wrap gap-8">
                <div v-for="project in myProjects" :key="project._id">
                    <div class="flex flex-col bg-white rounded-lg shadow p-4 justify-around" :style="{'aspectRatio': '1/1' , 'width': '170px'}">
                        <h3 class="text-xl font-bold" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap' }" v-tooltip="project.name">{{ project.name }}</h3>
                        <div class="flex items-center justify-between">
                            <div class="">
                                <Link :href="route('projects.edit', project._id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Details</Link>
                            </div>
                            <div class="inline-block rounded-full h-10 w-10 text-white flex items-center justify-center text-xl font-bold" :style="{backgroundColor: stringToColour(project.user.name)}" v-tooltip="project.user.name">
                                {{ project.user.name.charAt(0) }}
                            </div>
                        </div>
                    </div>
                </div>
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
            <div class="flex flex-wrap gap-8">
                <div v-for="project in projects" :key="project._id">
                    <div class="flex flex-col bg-white rounded-lg shadow p-4 justify-around" :style="{'aspectRatio': '1/1' , 'width': '170px'}">
                            <h3 class="text-xl font-bold" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap' }" v-tooltip="project.name">{{ project.name }}</h3>
                                <div class="flex items-center justify-between">
                                <div class="">
                                    <Link :href="route('projects.edit', project._id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Details</Link>
                                </div>
                                <div class="inline-block rounded-full h-10 w-10 text-white flex items-center justify-center text-xl font-bold" :style="{ backgroundColor: stringToColour(project.user.name) }" v-tooltip="project.user.name">
                                    {{ project.user.name.charAt(0) }}
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </section>
</template>
