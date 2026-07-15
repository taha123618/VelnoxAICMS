<template>
    <UCard>
        <template #header>
            <h1>Visits by browser type</h1>
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
        accessorKey: 'browser',
        header: 'Browser'
    },
    {
        accessorKey: 'total',
        header: 'Visits',
    }
]
</script>

<style scoped></style>