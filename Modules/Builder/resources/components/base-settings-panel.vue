<template>
    <UCard variant="solid" :ui="{
        header: 'p-2 sm:p-2 sm:py-2',
        body: 'p-2 sm:p-2 dark'
    }">
        <template #header>
            <div class="flex items-center gap-2 justify-between border-b border-neutral-700 py-4">
                <h1 class="font-medium">{{ title }}</h1>
                <slot name="aside" />
            </div>
        </template>
        <div class="flex-1">
            <slot />
        </div>
    </UCard>
</template>

<script setup lang="ts">
defineProps<{
    title: string
}>()
</script>

<style scoped></style>