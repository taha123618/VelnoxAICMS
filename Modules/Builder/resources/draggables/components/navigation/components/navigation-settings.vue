<template>
    <div class="space-y-2">        
        <BuilderSelect
            label-position="left"
            label="Menu"
            label-field="name"
            value-field="id"
            :options="menus"
            @change="element.setProps('menuId', $event)"
            :value="element.getProp('menuId')"
        />
    </div>
</template>

<script setup lang="ts">
import BuilderSelect from '@modules/Builder/resources/components/form/builder-select.vue';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
const { element } = defineProps<{
    element: ZioraElement;
}>();

const menus = computed<Modules.Menu.Data.MenuData[] | unknown>(() => (usePage().props.menus || []) as Modules.Menu.Data.MenuData);

</script>

<style scoped></style>
