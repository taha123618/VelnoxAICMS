<template>
    <UCard :ui="{
        body: 'p-0 sm:p-0'
    }">
        <template #header>
            Visitors by Country
        </template>
        <MapChart :data="data" legend-value-suffix="&nbsp;visitors"/>
    </UCard>
</template>

<script setup lang="ts">
import { MapChart } from 'vue3-map-chart'
import 'vue3-map-chart/dist/style.css'
import { MapData } from 'vue3-map-chart/types/types.js';

defineProps<{
    data: MapData
}>()

</script>

<style scoped></style>