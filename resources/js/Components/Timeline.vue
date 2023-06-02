<template>
    <div class="relative" v-tooltip="progressPercentage">
        <div class="progress-bar absolute">
            <div class="progress" :style="{ width: progressPercentage, backgroundColor: progressColor }">
            </div>
        </div>
        <span class="progress-info text-xs absolute">
            {{ startDate.getDate() }} {{ startDate.toLocaleString('default', { month: 'short' }) }} - {{
                dueDate.getDate() }} {{ dueDate.toLocaleString('default', { month: 'short' }) }}
        </span>
    </div>
</template>


<script setup>
import { ref, computed } from 'vue';

// Props
const props = defineProps({
    startDate: {
        type: Date,
        required: true
    },
    dueDate: {
        type: Date,
        required: true
    },
    projectColor: {
        type: String,
        required: true
    },
    status: {
        type: String,
        required: false
    }
});

const startDate = ref(new Date(
    parseInt(props?.startDate?.$date.$numberLong)
));
const dueDate = ref(new Date(
    parseInt(props?.dueDate?.$date.$numberLong)
));

const status = ref(props.status);

const projectColor = ref(props.projectColor);
// Data
const currentDate = ref(new Date());

// Computed Properties
const daysRemaining = computed(() => {
    const timeDiff = dueDate.value.getTime() - currentDate.value.getTime();
    return Math.ceil(timeDiff / (1000 * 3600 * 24));
});

const totalDuration = computed(() => {
    const timeDiff = dueDate.value.getTime() - startDate.value.getTime();
    return Math.ceil(timeDiff / (1000 * 3600 * 24));
});

const progressPercentage = computed(() => {
    const percentage = ((totalDuration.value - daysRemaining.value) / totalDuration.value) * 100;
    if (percentage < 0) {
        return '0%';
    }
    if (percentage > 100) {
        return '100%';
    }
    return `${Math.ceil(percentage)}%`;
});

const progressColor = computed(() => {
    if (status.value === 'Completed') {
        return projectColor.value;
    }
    if (daysRemaining.value < 0) {
        return 'rgb(248 113 113)';
    }
    if (daysRemaining.value < 3) {
        return 'rgb(250 204 21)';
    }
    return projectColor.value;
});
</script>

<style scoped>
.progress-bar {
    width: 100%;
    height: 36px;
    background-color: transparent;
    overflow: hidden;
    top: -18px
}

.progress {
    height: 100%;
    transition: width 0.3s ease;
}

.progress-info {
    top: -8px;
    width: 100%;
    text-align: center;
}
</style>

