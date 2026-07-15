<template>
    <UCard>
        <template #header>
            <h1>Visits by URL</h1>
        </template>
        <UTable
            :columns="columns"
            :data="data"
            class="flex-1"
        />
    </UCard>
</template>

<script setup lang="ts">
defineProps<{
    data: any[]
}>()


const columns = [
    {
        accessorKey: 'url',
        header: 'URL'
    },
    {
        accessorKey: 'total',
        header: 'Visits',
    }
]
</script>

<style scoped></style>