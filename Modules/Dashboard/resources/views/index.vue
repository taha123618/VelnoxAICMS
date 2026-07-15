<template>
    <AdminLayout :breadcrumbs="breadcrumbs">

        <Head title="Dashboard" />


        <div class="space-y-6">
            <UAlert
                :ui="{
                    title: 'text-3xl'
                }"
                :title="`Welcome ${user.first_name}!`"
                description="Manage your site content with Ziora CMS."
                color="primary"
                variant="soft"
                orientation="vertical"
            >

                <template #description>
                    <div class="text-neutral-700 py-4 text-lg">
                        <p>To get started and before launching the website builder, there are a few steps to follow to
                            start building your site.
                            <UPopover
                                v-model:open="isPopoverOpen"
                                :ui="{
                                    arrow: 'fill-[--ui-bg]'
                                }"
                                arrow
                                class="dark"
                            >
                                <UButton
                                    label="Here's how to get started."
                                    color="primary"
                                    variant="link"
                                />

                                <template #content>
                                    <GetStartedTimeline @close="isPopoverOpen = false" />
                                </template>
                            </UPopover>
                        </p>
                    </div>
                </template>
            </UAlert>
            <div class="grid md:grid-cols-2 gap-4">
                <!-- prettier-ignore -->
                <AnalyticsMap :data="((data.country_data) as unknown as MapData)" />

                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-2 gap-4 w-full">
                        <UCard class="bg-primary/10 dark:bg-primary/20 shadow-none border border-primary/20 dark:border-primary/30">
                            <p class="text-sm text-primary-700 dark:text-primary-300 font-medium">Page views</p>
                            <h3 class="text-2xl font-bold text-primary-900 dark:text-primary-50">{{ data.stats.total_pageviews }}</h3>
                        </UCard>

                        <UCard class="bg-primary/10 dark:bg-primary/20 shadow-none border border-primary/20 dark:border-primary/30">
                            <p class="text-sm text-primary-700 dark:text-primary-300 font-medium">Unique visitors</p>
                            <h3 class="text-2xl font-bold text-primary-900 dark:text-primary-50">{{ data.stats.unique_visitors }}</h3>
                        </UCard>

                        <UCard class="bg-error/10 dark:bg-error/20 border border-error/20 dark:border-error/30 shadow-none">
                            <p class="text-sm text-error-700 dark:text-error-300 font-medium">Total pages</p>
                            <div class="text-2xl font-bold text-error-900 dark:text-error-50">
                                {{ data.stats.total_pages }}
                                <span class="text-xs text-error-600 dark:text-error-400 font-normal">
                                    {{ data.stats.pending_pages }}
                                    with unpublished changes
                                </span>
                            </div>
                            <UButton
                                size="sm"
                                color="error"
                                class="px-0"
                                variant="link"
                                as-child
                            >
                                <Link
                                    class="flex group items-center gap-2"
                                    :href="route('admin.pages.index')"
                                >
                                Details
                                <UIcon
                                    name="ph:arrow-right"
                                    class="group-hover:ml-1 transition-all duration-300"
                                />
                                </Link>
                            </UButton>
                        </UCard>

                        <UCard class="bg-error/10 dark:bg-error/20 border border-error/20 dark:border-error/30 shadow-none">
                            <p class="text-sm text-error-700 dark:text-error-300 font-medium">Pending posts</p>
                            <div class="text-2xl font-bold text-error-900 dark:text-error-50">
                                {{ data.stats.total_posts }}
                                <span class="text-xs text-error-600 dark:text-error-400 font-normal">
                                    {{ data.stats.pending_posts }}
                                    with unpublished changes
                                </span>
                            </div>
                            <UButton
                                size="sm"
                                color="error"
                                class="px-0"
                                variant="link"
                                as-child
                            >
                                <Link
                                    class="flex group items-center gap-2"
                                    :href="route('admin.posts.index')"
                                >
                                Details
                                <UIcon
                                    name="ph:arrow-right"
                                    class="group-hover:ml-1 transition-all duration-300"
                                />
                                </Link>
                            </UButton>
                        </UCard>
                    </div>
                    <AnalyticsChart :data="data.visits_over_time" />
                </div>

                <VisitsByUrl :data="data.visits_by_url" />

                <VisitsByBrowser :data="data.visits_by_browser" />
            </div>

        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { useAuth } from '@modules/Auth/resources/composables/use-auth';
import type { BreadcrumbItem } from '@nuxt/ui'
import AnalyticsMap from '@modules/Dashboard/resources/components/AnalyticsMap.vue';
import { MapData } from 'vue3-map-chart/types/types.js';
import AnalyticsChart from '@modules/Dashboard/resources/components/AnalyticsChart.vue';
import GetStartedTimeline from '@modules/Dashboard/resources/components/GetStartedTimeline.vue';
import VisitsByUrl from '@modules/Dashboard/resources/components/VisitsByUrl.vue';
import VisitsByBrowser from '@modules/Dashboard/resources/components/VisitsByBrowser.vue';


defineProps<{
    data: Modules.Dashboard.Data.DashboardData,
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        label: 'Dashboard',
        icon: 'ph:house-line'
    },
];

const user = useAuth()
const isPopoverOpen = ref(false)


</script>

<style scoped></style>