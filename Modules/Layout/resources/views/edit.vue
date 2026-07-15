<template>
    <Head>
        <title>Layout: {{ layout.name }}</title>
    </Head>
    <PageBuilder
        type="layout"
        :editable="layout" />
</template>

<script setup lang="ts">
import PageBuilder from '@modules/Builder/resources/views/page-builder.vue';

const { layout } = defineProps<{
    layout: Modules.Layout.Data.LayoutData;
    menus: Modules.Menu.Data.MenuData[]
}>();
</script>

<style scoped></style>
