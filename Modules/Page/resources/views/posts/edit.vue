<template>

    <Head>
        <title>{{ post.title }}</title>
        <!-- prettier-ignore -->
        <meta
            head-key="description"
            name="description"
            :content="(post.description as string | undefined)"
        >
        <meta
            head-key="keywords"
            name="keywords"
            :content="post.keywords?.toString()"
        />
    </Head>
    <PageBuilder
        type="post"
        :layout="layout"
        :editable="post"
    />
</template>

<script setup lang="ts">
import PageBuilder from '@modules/Builder/resources/views/page-builder.vue';

const { post, layout } = defineProps<{
    post: Modules.Page.Data.PostData;
    menus: Modules.Menu.Data.MenuData[];
    layout?: Modules.Layout.Data.LayoutData | null;
}>();
</script>

<style scoped></style>
