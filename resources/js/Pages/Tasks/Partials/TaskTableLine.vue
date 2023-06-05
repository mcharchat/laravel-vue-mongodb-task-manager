<template>
    <td style="background-color: white;"
    :style="{
        color: projectTextColor,
    }"
    >
        <Icon 
            :icon="checkbox ? 'mdi:checkbox-marked' : 'mdi:checkbox-blank-outline'" 
            :class="{
                'transition-all cursor-pointer mx-1': true,
            }"
            @click="toggleCheckbox" 
        />
    </td>
    <td class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
        }"
    >
        <div class="flex items-center">
            <div class="w-[4px] h-[36px]" :style="{
                backgroundColor: projectColor,
            }"></div>
            <Icon class="m-3" :icon="publicc ? 'material-symbols:lock-open-right-outline' : 'material-symbols:lock-outline'" v-tooltip="publicc ? 'Public' : 'Private'"/>
            <div class="pr-3 py-2" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap'}" v-tooltip="props.item.title">
                {{ props.item.title }}
            </div>
        </div>
    </td>
    <td class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
        }"
    >
        <div class="flex w-[100px]">
            <div class="px-3 py-2 w-[100px]" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap'}" v-tooltip="props.item.user.name">
                {{ props.item.user.name }}
            </div>
        </div>
    </td>
    <td class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
        }"
    >
        <div class="flex w-[100px]">
            <div class="px-3 py-2 w-[100px]" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap' }" v-tooltip="users[props.item.assigned_to]?.name ?? 'Unassigned'">
                {{ users[props.item.assigned_to]?.name ?? 'Unassigned' }}
            </div>
        </div>
    </td>
    <td class=" text-sm font-medium text-white dark:text-white p-0"
        :class="statusColorDictionary[props.item.status]"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
        }"
    >
        <div class="flex w-[100px]">
            <div class="px-3 py-2 w-[100px]" :style="{ 'overflow': 'hidden', 'text-overflow': 'ellipsis', 'white-space': 'nowrap' }" v-tooltip="props.item.status">
                {{ props.item.status }}
            </div>
        </div>
    </td>
    <td class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
        }"
    >
        <div class="flex w-[120px]">
            <div class="w-[120px] " >
                <Timeline v-show="(props.item.start_date !== undefined) && (props.item.due_date !== undefined) " :startDate="props.item.start_date" :dueDate="props.item.due_date" :projectColor="projectColor" :status="props.item.status"/>
            </div>
        </div>
    </td>
    <td class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
        }"
    >
        <div class="flex w-[120px]">
            <div class="w-[120px] " >
                <PercCompleted v-show="props.item.progress !== undefined" :progress="props.item.progress" :projectColor="projectColor"/>
            </div>
        </div>
    </td>
    <td class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
        }"
        v-tooltip="props.item.priority"
    >
        <div class="flex w-[120px] justify-center">
            <Icon 
                :icon="PriorityDictionary[props.item.priority]?.icon" 
                class="mx-1"
                :class="PriorityDictionary[props.item.priority]?.color"
                @click="toggleCheckbox" 
                
            />
        </div>
    </td>
    <td class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0 min-w-[120px]"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
        }"
    >
        <div v-show="props.item.team" class="flex justify-center gap-2 px-2">
                <div v-for="user_id in props.item.team" :key="user_id" class="inline-block rounded-full h-[24px] w-[24px] text-white flex items-center justify-center text-sm font-bold" :style="{ backgroundColor: stringToColour(users[user_id]?.name) }" v-tooltip="users[user_id]?.name">
                    {{ users[user_id]?.name.charAt(0) }}
                </div>
        </div>
    </td>
    <td class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0 min-w-[120px]"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
        }"
    >
        <div v-show="props.item.labels" class="flex justify-center gap-2 px-2">
                <div v-for="(label, index) in props.item.labels" :key="index" 
                    class="inline-block rounded-full h-[24px] text-white flex items-center justify-center text-xs font-bold px-3 py-1" 
                    :class="`bg-${label.color}-400`"
                    v-tooltip="label.name"
                >
                    <span>
                        {{ label.name }}
                    </span>
                </div>
        </div>
    </td>
    <td class=" text-sm font-medium text-gray-900 dark:text-gray-100 p-0"
        :style="{
            borderBottom: '2px solid white',
            borderRight: '2px solid white',
            color: projectTextColor,
        }"
    >
        <div class="flex justify-center gap-4 px-4">
            <Icon icon="mdi:close" class="cursor-pointer" style="filter: invert(1)" v-tooltip="'Delete'"></Icon>
            <Icon icon="mdi:dots-vertical" class="cursor-pointer" style="filter: invert(1)" v-tooltip="'More Options'"></Icon>
        </div>
    </td>
</template>

<script setup>
import { Icon } from '@iconify/vue';
import { ref } from 'vue';
import Timeline from '@/Components/Timeline.vue';
import PercCompleted from '@/Components/PercCompleted.vue';
import { usePage } from '@inertiajs/vue3';
import { stringToColour } from '@/Utils/globalFunctions';

const props = defineProps({
    item: {
        type: Object,
        required: true
    },
    projectColor: {
        type: String,
        required: true
    },
    projectTextColor: {
        type: String,
        required: true
    }
});

const users = usePage().props.users;

const checkbox = ref(false);

const publicc = ref(props.item.public);

const toggleCheckbox = () => {
    checkbox.value = !checkbox.value;
};

const statusColorDictionary = {
    'Not Started': 'bg-red-400',
    'In Progress': 'bg-yellow-400',
    'Completed': 'bg-green-400',
    'On Hold': 'bg-blue-400',
    'Cancelled': 'bg-gray-400',
};

const PriorityDictionary = {
    'None': {
        'color': 'text-gray-400',
        'icon': 'mdi:checkbox-blank-circle-outline',
    },
    'Lowest': {
        'color': 'text-green-400',
        'icon': 'mdi:chevron-triple-down',
    },
    'Low': {
        'color': 'text-green-400',
        'icon': 'mdi:chevron-double-down',
    },
    'Medium': {
        'color': 'text-yellow-400',
        'icon': 'mdi:equal',
    },
    'High': {
        'color': 'text-red-400',
        'icon': 'mdi:chevron-double-up',
    },
    'Highest': {
        'color': 'text-red-400',
        'icon': 'mdi:chevron-triple-up',
    },
};
</script>
