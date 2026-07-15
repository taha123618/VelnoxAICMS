<template>

    <Head>
        <title>{{ page.title }}</title>
        <!-- prettier-ignore -->
        <meta
            head-key="description"
            name="description"
            :content="(page.description as string | undefined)"
        >
        <meta
            head-key="keywords"
            name="keywords"
            :content="page.keywords?.toString()"
        />
    </Head>

    <PageBuilder
        type="page"
        :layout="layout"
        :editable="page"
    />
</template>

<script setup lang="ts">
import PageBuilder from '@modules/Builder/resources/views/page-builder.vue';

const { page, layout } = defineProps<{
    page: Modules.Page.Data.PageData;
    layout?: Modules.Layout.Data.LayoutData | null;
    menus: Modules.Menu.Data.MenuData[]
}>();
</script>

<style scoped></style>
