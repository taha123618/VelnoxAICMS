<template>
    <UCard
        :ui="{
            body: 'p-0 sm:p-0',
            header: 'p-1.5 sm:py-1.5',
        }">
        <template #header>
            <div class="flex items-center justify-between">
                <h1>Page views over time</h1>
                <div>
                    <!-- <URadioGroup
                        size="xs"
                        indicator="hidden"
                        color="primary"
                        orientation="horizontal"
                        variant="table"
                        default-value="all"
                        :items="filterOptions"
                    /> -->
                </div>
            </div>
        </template>

        <apex-chart
            style="height: 100%"
            :height="250"
            :options="options"
            :series="series"></apex-chart>
    </UCard>
</template>

<script setup lang="ts">
// import { RadioGroupItem } from "@nuxt/ui";
import type { ApexOptions } from 'apexcharts';
import ApexChart from 'vue3-apexcharts';

const props = defineProps<{
    data: Record<string, any>[];
}>();

const isDark = useDark();

const options = computed<ApexOptions>(() => ({
    chart: {
        id: 'vuechart-example',
        type: 'area',
        zoom: {
            enable: false,
            autoScaleYaxis: true,
            allowMouseWheelZoom: false,
        },
        toolbar: {
            show: true,
        },
    },
    theme: {
        mode: isDark.value ? 'dark' : 'light',
    },
    colors: ['#7b85ff'],
    stroke: {
        show: true,
        width: 1,
        curve: 'smooth',
        colors: ['#7b85ff'],
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 1,
            stops: [0, 100],
        },
    },
    responsive: [
        {
            breakpoint: undefined,
            options: {},
        },
    ],
    xaxis: {
        type: 'datetime',
    },
    dataLabels: {
        enabled: false,
    },
}));

const series = computed(() => [
    {
        name: 'Page views',
        data: props.data,
    },
]);

// const filterOptions = ref<RadioGroupItem[]>([
//     {
//         label: '3 mon.',
//         value: '3mon',
//     },
//     {
//         label: '6 mon.',
//         value: '6mon',
//     },
//     {
//         label: 'YTD',
//         value: 'ytd',
//     },
//     {
//         label: 'All',
//         value: 'all',
//     },
// ])
</script>

<style scoped></style>
