<template>
    <div class="text-sm text-neutral-400">
        {{ data.from }} - {{ data.to }} of {{ data.total }} records
    </div>
    <div>
        <UPagination
            size="sm"
            :default-page="data.current_page"
            :items-per-page="data.per_page"
            :total="data.total"
            v-model:page="model"
            color="neutral"
            variant="soft"
            active-variant="subtle" />
    </div>
</template>

<script setup lang="ts">
import { ITableData } from '@/types/types';

const { data } = defineProps<{
    data: ITableData;
}>();

const model = defineModel<number | unknown>();
</script>

<style scoped></style>
