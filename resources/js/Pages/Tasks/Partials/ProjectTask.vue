
<script setup>
import { ref } from 'vue';
import { Icon } from '@iconify/vue';
import TaskTableLine from './TaskTableLine.vue';
import { stringToColour } from '@/Utils/globalFunctions';

const props = defineProps({
    project: {
        type: Array,
        required: true
    }
});
const project = ref(props.project);


const collapsed = ref(false);
const projectName = (project.value[0]['project'] != null ? project.value[0]['project']?.name : 'default');
const projectColor = stringToColour(projectName);
const projectBackgroundColor = projectColor + '1a';
const projectTextColor = projectColor + 'e6';

const toggleCollapse = () => {
    collapsed.value = !collapsed.value;
};

</script>

<template>
    <div class="mb-1 overflow-x-auto">
        <table class="">
            <thead>
                
                <transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <tr>
                        <th>
                            <Icon icon="iconamoon:arrow-up-2-bold" :class="{
                                'transition-all mx-1 cursor-pointer': true,
                                'transform rotate-180': collapsed,
                            }"
                            :style="{ color: projectTextColor }"
                            @click="toggleCollapse"/>
                        </th>
                        <th class="text-start px-1 text-lg"
                            :style="{ color: projectTextColor }"
                        >
                            {{ project[0].project?.name }}
                        </th>
                        <th v-if="!collapsed" class="px-1 text-base font-medium">Owner</th>
                        <th v-if="!collapsed" class="px-1 text-base font-medium">Assingee</th>
                        <th v-if="!collapsed" class="px-1 text-base font-medium">Status</th>
                        <th v-if="!collapsed" class="px-1 text-base font-medium">Timeline</th>
                        <th v-if="!collapsed" class="px-1 text-base font-medium">% Completed</th>
                        <th v-if="!collapsed" class="px-1 text-base font-medium">Priority</th>
                        <th v-if="!collapsed" class="px-1 text-base font-medium">Team</th>
                    </tr>
                </transition>
            </thead>
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <tbody v-if="!collapsed" >
                    <tr v-for="item in project" :key="item._id" :style="{backgroundColor: projectBackgroundColor}">
                        <TaskTableLine :item="{...item}" :projectColor="projectColor" :projectTextColor="projectTextColor"/>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="100%" class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0"
                            :style="{
                                borderBottom: '2px solid white',
                                borderRight: '2px solid white',
                            }"
                        >
                            <div class="flex">
                                <div class="w-[4px]" :style="{
                                    backgroundColor: projectBackgroundColor,
                                }"></div>
                                <div class="cursor-pointer px-3 py-2 grow" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap' }">
                                    + Add
                                </div>
                            </div>
                        </td> 
                    </tr>
                </tbody>
            </transition>
        </table>
    </div>
</template>
