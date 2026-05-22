<script setup>
import EmptyState from '../components/shared/EmptyState.vue';
import ErrorMessage from '../components/shared/ErrorMessage.vue';
import LoadingState from '../components/shared/LoadingState.vue';
import Pagination from '../components/shared/Pagination.vue';
import OrdersTable from '../components/orders/OrdersTable.vue';
import OrderStatusFilter from '../components/orders/OrderStatusFilter.vue';
import { useOrders } from '../composables/useOrders';

const {
    orders,
    meta,
    loading,
    error,
    successMessage,
    assigningOrderIds,
    filters,
    setStatusFilter,
    goToPage,
    assignPendingOrder,
} = useOrders();
</script>

<template>
    <section class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-zinc-950">Orders</h2>
                <p class="mt-1 text-sm text-zinc-600">Review transport requests and assign pending orders.</p>
            </div>

            <OrderStatusFilter
                :model-value="filters.status"
                :disabled="loading"
                @update:model-value="setStatusFilter"
            />
        </div>

        <div v-if="successMessage" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ successMessage }}
        </div>

        <ErrorMessage v-if="error" :message="error" />
        <LoadingState v-if="loading && orders.length === 0" />
        <EmptyState
            v-else-if="!loading && orders.length === 0"
            title="No orders found"
            message="There are no orders matching the current status filter."
        />

        <div v-else>
            <OrdersTable
                :orders="orders"
                :assigning-order-ids="assigningOrderIds"
                @assign-order="assignPendingOrder"
            />
            <Pagination :meta="meta" :disabled="loading" @page-change="goToPage" />
        </div>
    </section>
</template>
