<template>
    <UApp>
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
                :content="page.keywords?.toString()" />
        </Head>
        <div
            v-if="isLoading"
            class="fixed inset-0 z-50 flex items-center justify-center bg-white">
            <!-- Replace with your logo or a spinner -->
            <img
                src="/assets/images/logo-icon.svg"
                alt="Loading..."
                class="size-14 animate-spin" />
        </div>
        <div
            v-else
            class="@container flex min-h-svh w-full bg-white transition-opacity duration-500"
            :style="{ 'container-type': 'inline-size' }">
            <BaseRecursiveElement
                v-for="layoutElement in store.renderableElements"
                :key="layoutElement.id"
                :element="layoutElement" />
        </div>
    </UApp>
</template>

<script setup lang="ts">
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import { extractStyles } from '@modules/Builder/resources/scripts/factory';
import { TElement } from '@modules/Builder/resources/scripts/types';
import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { useHead } from '@unhead/vue';

const { page, layout } = defineProps<{
    page: Modules.Page.Data.PageData;
    layout: Modules.Layout.Data.LayoutData;
}>();

const store = useVelnoxAI();

const isLoading = ref<boolean>(true);

// Memoize style extraction for performance
const computedLayoutStyles = computed(() =>
    extractStyles(store.layoutElements || []),
);
const computedEditorStyles = computed(() =>
    extractStyles(store.editorElements),
);

useHead({
    style: [
        {
            textContent: computedLayoutStyles,
            id: 'live_layout_styles',
        },
        {
            textContent: computedEditorStyles,
            id: 'live_element_styles',
        },
    ],
});

onMounted(() => {
    setTimeout(() => (isLoading.value = false), 300);
});

onBeforeMount(() => {
    store.disableEditor();
    const pageContent = page.content!.map((item: TElement) =>
        markRaw(VelnoxAIElement.fromObject(item)),
    );

    const layoutContent = layout.content!.map((item: TElement) =>
        markRaw(VelnoxAIElement.fromObject(item)),
    );

    store.setLayoutElements(layoutContent);
    store.setInitialElements(pageContent, 'page');
});
</script>

<style scoped></style>
