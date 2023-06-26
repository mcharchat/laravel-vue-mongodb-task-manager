/**
 * A component that displays a summary of a task, including its icon, title, and completion status.
 *
 * @props {String} icon - The name of the icon to display.
 * @props {String} title - The title of the task.
 * @props {String} color - The background color of the summary box.
 * @props {Number} completed - The number of completed subtasks.
 * @props {Number} pending - The number of pending subtasks.
 */
<template>
    <div class="flex bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
        <div class="p-6 text-white text-sm font-light flex flex-col items-center justify-center aspect-square w-28"
            :style="{
                backgroundColor: color,
            }"
        >
            <Icon :icon="icon" width="24" height="24" />
            <span class="text-center">
                {{ title }}
            </span>
        </div>
        <div class="grow "></div>
    </div>
</template>
<script setup>
import { Icon } from '@iconify/vue';
const props = defineProps({
    icon: String,
    title: String,
    color: String,
    completed: Number,
    pending: Number,
});
</script>