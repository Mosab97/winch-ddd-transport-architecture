<script setup>
import { computed } from 'vue';

const props = defineProps({
    meta: {
        type: Object,
        default: () => ({}),
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['page-change']);

const currentPage = computed(() => props.meta.current_page || 1);
const lastPage = computed(() => props.meta.last_page || 1);
const total = computed(() => props.meta.total || 0);
const from = computed(() => props.meta.from || 0);
const to = computed(() => props.meta.to || 0);
const canGoPrevious = computed(() => currentPage.value > 1 && !props.disabled);
const canGoNext = computed(() => currentPage.value < lastPage.value && !props.disabled);

function changePage(page) {
    if (!props.disabled && page >= 1 && page <= lastPage.value && page !== currentPage.value) {
        emit('page-change', page);
    }
}
</script>

<template>
    <div
        v-if="lastPage > 1 || total > 0"
        class="flex flex-col gap-3 border-t border-zinc-200 bg-white px-4 py-3 text-sm text-zinc-600 sm:flex-row sm:items-center sm:justify-between"
    >
        <p>
            Showing {{ from }} to {{ to }} of {{ total }} results
        </p>

        <div class="flex items-center gap-2">
            <button
                type="button"
                class="rounded-md border border-zinc-300 px-3 py-2 font-medium text-zinc-700 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="!canGoPrevious"
                @click="changePage(currentPage - 1)"
            >
                Previous
            </button>
            <span class="min-w-24 text-center">Page {{ currentPage }} of {{ lastPage }}</span>
            <button
                type="button"
                class="rounded-md border border-zinc-300 px-3 py-2 font-medium text-zinc-700 transition hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="!canGoNext"
                @click="changePage(currentPage + 1)"
            >
                Next
            </button>
        </div>
    </div>
</template>
