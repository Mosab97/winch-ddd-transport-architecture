<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import OrderStatusFilter from '../components/orders/OrderStatusFilter.vue';
import OrdersTable from '../components/orders/OrdersTable.vue';
import EmptyState from '../components/shared/EmptyState.vue';
import ErrorMessage from '../components/shared/ErrorMessage.vue';
import LoadingState from '../components/shared/LoadingState.vue';
import Pagination from '../components/shared/Pagination.vue';
import { useDriverOrders } from '../composables/useDriverOrders';

const route = useRoute();
const driverId = computed(() => route.params.driverId);

const {
    orders,
    meta,
    loading,
    error,
    filters,
    setStatusFilter,
    goToPage,
} = useDriverOrders(driverId);
</script>

<template>
    <section class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <RouterLink to="/drivers" class="text-sm font-medium text-teal-700 hover:text-teal-800">
                    Back to drivers
                </RouterLink>
                <h2 class="mt-2 text-2xl font-semibold text-zinc-950">Driver #{{ driverId }} Orders</h2>
                <p class="mt-1 text-sm text-zinc-600">Review orders assigned to this driver.</p>
            </div>

            <OrderStatusFilter
                :model-value="filters.status"
                :disabled="loading"
                @update:model-value="setStatusFilter"
            />
        </div>

        <ErrorMessage v-if="error" :message="error" />
        <LoadingState v-if="loading && orders.length === 0" />
        <EmptyState
            v-else-if="!loading && orders.length === 0"
            title="No driver orders found"
            message="This driver has no orders matching the current status filter."
        />

        <div v-else>
            <OrdersTable :orders="orders" :show-actions="false" />
            <Pagination :meta="meta" :disabled="loading" @page-change="goToPage" />
        </div>
    </section>
</template>
