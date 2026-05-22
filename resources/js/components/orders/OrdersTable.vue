<script setup>
defineProps({
    orders: {
        type: Array,
        required: true,
    },
    assigningOrderIds: {
        type: Object,
        default: () => ({}),
    },
    showActions: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['assign-order']);

function formatLocation(latitude, longitude) {
    if (latitude === null || latitude === undefined || longitude === null || longitude === undefined) {
        return 'Not available';
    }

    return `${Number(latitude).toFixed(4)}, ${Number(longitude).toFixed(4)}`;
}

function formatDate(value) {
    if (!value) {
        return 'Not assigned';
    }

    return new Intl.DateTimeFormat('en', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}

function formatStatus(status) {
    return status.charAt(0).toUpperCase() + status.slice(1);
}
</script>

<template>
    <div class="overflow-hidden rounded-md border border-zinc-200 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 text-sm">
                <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase text-zinc-600">
                    <tr>
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Assigned Driver</th>
                        <th class="px-4 py-3">Pickup Location</th>
                        <th class="px-4 py-3">Assigned Date</th>
                        <th class="px-4 py-3">Created Date</th>
                        <th v-if="showActions" class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    <tr v-for="order in orders" :key="order.id" class="hover:bg-zinc-50">
                        <td class="whitespace-nowrap px-4 py-4 font-medium text-zinc-950">#{{ order.id }}</td>
                        <td class="whitespace-nowrap px-4 py-4">
                            <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-700">
                                {{ formatStatus(order.status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-4 text-zinc-700">
                            {{ order.driver?.name || (order.driver_id ? `Driver #${order.driver_id}` : 'Unassigned') }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-4 text-zinc-700">
                            {{ formatLocation(order.pickup_latitude, order.pickup_longitude) }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-4 text-zinc-700">{{ formatDate(order.assigned_at) }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-zinc-700">{{ formatDate(order.created_at) }}</td>
                        <td v-if="showActions" class="whitespace-nowrap px-4 py-4 text-right">
                            <button
                                v-if="order.status === 'pending'"
                                type="button"
                                class="rounded-md bg-teal-700 px-3 py-2 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60"
                                :disabled="assigningOrderIds[order.id]"
                                @click="emit('assign-order', order.id)"
                            >
                                {{ assigningOrderIds[order.id] ? 'Assigning...' : 'Assign Driver' }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
