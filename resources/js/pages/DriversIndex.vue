<script setup>
import DriversTable from '../components/drivers/DriversTable.vue';
import EmptyState from '../components/shared/EmptyState.vue';
import ErrorMessage from '../components/shared/ErrorMessage.vue';
import LoadingState from '../components/shared/LoadingState.vue';
import Pagination from '../components/shared/Pagination.vue';
import { useDrivers } from '../composables/useDrivers';

const {
    drivers,
    meta,
    loading,
    error,
    goToPage,
} = useDrivers();
</script>

<template>
    <section class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-zinc-950">Drivers</h2>
            <p class="mt-1 text-sm text-zinc-600">View driver availability and inspect assigned orders.</p>
        </div>

        <ErrorMessage v-if="error" :message="error" />
        <LoadingState v-if="loading && drivers.length === 0" />
        <EmptyState
            v-else-if="!loading && drivers.length === 0"
            title="No drivers found"
            message="No drivers are currently available in the system."
        />

        <div v-else>
            <DriversTable :drivers="drivers" />
            <Pagination :meta="meta" :disabled="loading" @page-change="goToPage" />
        </div>
    </section>
</template>
