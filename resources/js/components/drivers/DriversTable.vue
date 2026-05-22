<script setup>
function formatLocation(latitude, longitude) {
    if (latitude === null || latitude === undefined || longitude === null || longitude === undefined) {
        return 'Not available';
    }

    return `${Number(latitude).toFixed(4)}, ${Number(longitude).toFixed(4)}`;
}

function formatDate(value) {
    return new Intl.DateTimeFormat('en', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}

function formatStatus(status) {
    return status.charAt(0).toUpperCase() + status.slice(1);
}

defineProps({
    drivers: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div class="overflow-hidden rounded-md border border-zinc-200 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 text-sm">
                <thead class="bg-zinc-100 text-left text-xs font-semibold uppercase text-zinc-600">
                    <tr>
                        <th class="px-4 py-3">Driver ID</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Current Location</th>
                        <th class="px-4 py-3">Created Date</th>
                        <th class="px-4 py-3 text-right">Orders</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    <tr v-for="driver in drivers" :key="driver.id" class="hover:bg-zinc-50">
                        <td class="whitespace-nowrap px-4 py-4 font-medium text-zinc-950">#{{ driver.id }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-zinc-700">{{ driver.name }}</td>
                        <td class="whitespace-nowrap px-4 py-4">
                            <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-700">
                                {{ formatStatus(driver.status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-4 text-zinc-700">
                            {{ formatLocation(driver.latitude, driver.longitude) }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-4 text-zinc-700">{{ formatDate(driver.created_at) }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-right">
                            <RouterLink
                                :to="`/drivers/${driver.id}/orders`"
                                class="rounded-md border border-teal-700 px-3 py-2 text-sm font-semibold text-teal-700 transition hover:bg-teal-50"
                            >
                                View Orders
                            </RouterLink>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
