<template>
    <UCard
        variant="subtle"
        :ui="{
            header: 'p-2 sm:px-2.5 sm:py-2 text-sm',
            body: 'p-2 sm:px-2.5 sm:py-2',
        }"
    >
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="font-semibold">{{ group.name }}</h1>
            </div>
        </template>
        <UCheckboxGroup
            size="sm"
            value-key="id"
            label-key="label"
            :items="group.permissions"
            v-model="model"
        />
    </UCard>
</template>

<script setup lang="ts">

defineProps<{
    group: Record<string, any>;
}>()

const model = defineModel<string[]>()

</script>

<style scoped></style>