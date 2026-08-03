<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Site Visits & Analytics" />

        <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Site Visits & Analytics</h1>
                    <p class="mt-1 text-sm text-neutral-400">
                        Real-time page view tracking, unique visitors, and traffic sources.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex gap-3">
                    <UBadge color="success" variant="soft" size="lg">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2 inline-block"></span>
                        Live Tracking Active
                    </UBadge>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    v-for="metric in metrics"
                    :key="metric.label"
                    class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2"
                >
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>{{ metric.label }}</span>
                        <UIcon :name="metric.icon" :class="metric.iconClass" class="w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">{{ metric.value }}</div>
                    <div :class="metric.trendClass" class="text-[11px]">{{ metric.trend }}</div>
                </div>
            </div>

            <!-- Traffic Chart Placeholder -->
            <UCard class="border border-neutral-800 bg-neutral-900/60 shadow-xl">
                <template #header>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-white flex items-center gap-2">
                            <UIcon name="ph:chart-line-up" class="text-indigo-400" />
                            Traffic Overview (Last 30 Days)
                        </h3>
                        <div class="flex gap-2">
                            <UButton
                                v-for="period in ['7d','14d','30d']"
                                :key="period"
                                size="xs"
                                :color="activePeriod === period ? 'primary' : 'neutral'"
                                :variant="activePeriod === period ? 'solid' : 'ghost'"
                                @click.prevent="() => { activePeriod = period; }"  
                            >
                                {{ period }}
                            </UButton>
                        </div>
                    </div>
                </template>

                <div class="h-48 flex items-center justify-center text-neutral-600">
                    <div class="text-center space-y-2">
                        <UIcon name="ph:chart-bar" class="w-12 h-12 mx-auto opacity-30" />
                        <p class="text-sm">Chart visualization renders here</p>
                        <p class="text-xs text-neutral-700">Integrate ApexCharts or Chart.js via Nightwatch events</p>
                    </div>
                </div>
            </UCard>

            <!-- Top Pages Table -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <UCard class="border border-neutral-800 bg-neutral-900/60 shadow-xl">
                    <template #header>
                        <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                            <UIcon name="ph:globe-hemisphere-west" class="text-purple-400" />
                            Top Pages
                        </h3>
                    </template>
                    <div class="space-y-3">
                        <div
                            v-for="page in topPages"
                            :key="page.url"
                            class="flex items-center justify-between p-2.5 rounded-lg bg-neutral-800/40 hover:bg-neutral-800/60 transition-colors"
                        >
                            <div class="min-w-0">
                                <p class="text-sm text-white truncate font-mono">{{ page.url }}</p>
                                <p class="text-xs text-neutral-500">{{ page.title }}</p>
                            </div>
                            <div class="text-right flex-shrink-0 ml-4">
                                <span class="text-sm font-bold text-white">{{ page.views }}</span>
                                <p class="text-xs text-neutral-500">views</p>
                            </div>
                        </div>
                    </div>
                </UCard>

                <UCard class="border border-neutral-800 bg-neutral-900/60 shadow-xl">
                    <template #header>
                        <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                            <UIcon name="ph:device-mobile" class="text-amber-400" />
                            Devices & Browsers
                        </h3>
                    </template>
                    <div class="space-y-3">
                        <div
                            v-for="device in deviceBreakdown"
                            :key="device.name"
                            class="flex items-center gap-3"
                        >
                            <UIcon :name="device.icon" class="w-4 h-4 text-neutral-400 flex-shrink-0" />
                            <div class="flex-1">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-neutral-300">{{ device.name }}</span>
                                    <span class="text-neutral-400">{{ device.percentage }}%</span>
                                </div>
                                <div class="h-1.5 rounded-full bg-neutral-800 overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-primary-500 transition-all duration-700"
                                        :style="{ width: `${device.percentage}%` }"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </UCard>
            </div>

        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import type { BreadcrumbItem } from '@nuxt/ui';

defineProps<{
    visits?: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { label: 'Visits & Analytics', icon: 'ph:chart-line-up' },
];

const activePeriod = ref('30d');

const metrics = [
    {
        label: 'Total Page Views',
        value: '24,819',
        icon: 'ph:eye',
        iconClass: 'text-indigo-400',
        trend: '↑ 14.3% vs last month',
        trendClass: 'text-emerald-400',
    },
    {
        label: 'Unique Visitors',
        value: '8,204',
        icon: 'ph:users',
        iconClass: 'text-purple-400',
        trend: '↑ 9.2% vs last month',
        trendClass: 'text-emerald-400',
    },
    {
        label: 'Avg. Session Duration',
        value: '3m 42s',
        icon: 'ph:timer',
        iconClass: 'text-amber-400',
        trend: '↓ 0.8% vs last month',
        trendClass: 'text-red-400',
    },
    {
        label: 'Bounce Rate',
        value: '38.5%',
        icon: 'ph:arrow-u-up-left',
        iconClass: 'text-rose-400',
        trend: '↓ Good improvement',
        trendClass: 'text-emerald-400',
    },
];

const topPages = [
    { url: '/', title: 'Homepage', views: 8_412 },
    { url: '/blog', title: 'Blog Index', views: 3_201 },
    { url: '/marketplace', title: 'Marketplace', views: 2_541 },
    { url: '/about', title: 'About Us', views: 1_890 },
    { url: '/contact', title: 'Contact', views: 1_102 },
];

const deviceBreakdown = [
    { name: 'Desktop', icon: 'ph:desktop', percentage: 58 },
    { name: 'Mobile', icon: 'ph:device-mobile', percentage: 35 },
    { name: 'Tablet', icon: 'ph:device-tablet', percentage: 7 },
];
</script>

<style scoped></style>