<script setup>
const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

const statuses = [
    { value: '', label: 'All statuses' },
    { value: 'pending', label: 'Pending' },
    { value: 'assigned', label: 'Assigned' },
    { value: 'completed', label: 'Completed' },
    { value: 'cancelled', label: 'Cancelled' },
];

function updateStatus(event) {
    emit('update:modelValue', event.target.value);
}
</script>

<template>
    <label class="flex flex-col gap-2 text-sm font-medium text-zinc-700">
        Status
        <select
            class="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-100 sm:w-56"
            :value="props.modelValue"
            :disabled="disabled"
            @change="updateStatus"
        >
            <option v-for="status in statuses" :key="status.value" :value="status.value">
                {{ status.label }}
            </option>
        </select>
    </label>
</template>
