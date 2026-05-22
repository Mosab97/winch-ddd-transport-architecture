<script setup>
const navigation = [
    { name: 'Orders', to: '/orders' },
    { name: 'Drivers', to: '/drivers' },
];
</script>

<template>
    <div class="min-h-screen bg-zinc-50 text-zinc-950">
        <header class="border-b border-zinc-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <div>
                    <p class="text-sm font-semibold uppercase text-teal-700">WINCH Transport</p>
                    <h1 class="text-2xl font-semibold text-zinc-950">Dispatch Assignment System</h1>
                </div>

                <nav class="flex gap-2" aria-label="Primary navigation">
                    <RouterLink
                        v-for="item in navigation"
                        :key="item.to"
                        :to="item.to"
                        class="rounded-md px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-950"
                        active-class="bg-teal-700 text-white hover:bg-teal-700 hover:text-white"
                    >
                        {{ item.name }}
                    </RouterLink>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <RouterView />
        </main>
    </div>
</template>
