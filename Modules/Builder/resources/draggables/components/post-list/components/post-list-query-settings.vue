<template>
    <div class="space-y-4">
        <USwitch
            size="sm"
            @update:modelValue="element.setProps('query.fromCategories', $event)"
            :model-value="element.getProp('query.fromCategories')"
            label="Specific categories"
        />

        <BuilderSelect
            v-if="element.getProp('query.fromCategories')"
            label="Categories"
            :options="categories"
            option-is-object
            label-position="left"
            value-field="id"
            label-field="name"
            placeholder="Select categories..."
            :multiple="true"
            :value="element.getProp('query.categories')"
            @change="element.setProps('query.categories', $event)"
        />

        <BuilderNumberInput
            label="Item limit"
            label-position="left"
            :value="element.getProp('query.perPage')"
            @change="element.setProps('query.perPage', $event)"
        />

        <BuilderSelect
            label="Order by"
            :options="orderOptions"
            option-is-object
            label-position="left"
            :value="element.getProp('query.orderBy')"
            @change="element.setProps('query.orderBy', $event)"
        />

        <BuilderSelect
            label="Order direction"
            :options="orderDirectionOptions"
            option-is-object
            label-position="left"
            :value="element.getProp('query.orderDir')"
            @change="element.setProps('query.orderDir', $event)"
        />

    </div>
</template>

<script setup lang="ts">
import BuilderNumberInput from '@modules/Builder/resources/components/form/builder-number-input.vue';
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { useCategories } from '@modules/Category/resources/scripts/use-categories';

const { element } = defineProps<{
    element: ZioraElement;
}>();

const { categories } = useCategories();

const orderOptions = [
    { value: 'created_at', label: 'Date created' },
    { value: 'updated_at', label: 'Date updated' },
    { value: 'random', label: 'Random' },
];

const orderDirectionOptions = [
    { value: 'asc', label: 'Ascending' },
    { value: 'desc', label: 'Descending' },
];
</script>

<style scoped></style>
