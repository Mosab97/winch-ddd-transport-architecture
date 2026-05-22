<script setup>
defineProps({
    title: {
        type: String,
        default: 'No results found',
    },
    message: {
        type: String,
        default: 'Try changing the current filters or checking again later.',
    },
});
</script>

<template>
    <div class="rounded-md border border-dashed border-zinc-300 bg-white p-8 text-center">
        <h2 class="text-base font-semibold text-zinc-950">{{ title }}</h2>
        <p class="mt-2 text-sm text-zinc-600">{{ message }}</p>
    </div>
</template>
