<template>
    <BaseModalPage
        is-slide-over
        ref="modalRef"
        size="max-w-4xl"
        bodyClass="bg-neutral-100 dark:bg-neutral-900"
        title="Menu Builder"
        v-model="showModal"
    >
        <div class="grid grid-cols-5 items-start gap-4">
            <div class="col-span-2 border border-neutral-200 dark:border-neutral-800 rounded-lg">
                <UAccordion
                    variant="subtle"
                    :ui="{
                        header: 'px-4',
                        root: 'bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-lg'
                    }"
                    :items="accordionItems"
                >
                    <template #pages>
                        <div class="flex flex-col gap-1 p-4">
                            <BaseTooltip content="Add to menu">
                                <UButton
                                    class="justify-between"
                                    @click="addPageToMenu(page, 'page')"
                                    color="neutral"
                                    variant="subtle"
                                    trailing-icon="ph:plus-circle"
                                    v-for="page in pages"
                                    :key="page.id"
                                    size="md"
                                >
                                    {{ page.title }}
                                </UButton>
                            </BaseTooltip>
                        </div>
                    </template>
                    <template #posts>
                        <div class="flex flex-col gap-1 p-4">
                            <BaseTooltip content="Add to menu">
                                <UButton
                                    class="justify-between"
                                    @click="addPageToMenu(post, 'post')"
                                    color="neutral"
                                    variant="subtle"
                                    trailing-icon="ph:plus-circle"
                                    v-for="post in posts"
                                    :key="post.id"
                                    size="md"
                                >
                                    {{ post.title }}
                                </UButton>
                            </BaseTooltip>
                        </div>
                    </template>
                    <template #custom>
                        <form
                            @submit.prevent="addCustomLinkToMenu"
                            class="flex flex-col gap-1 p-4"
                        >
                            <UFormField
                                required
                                label="Link text"
                            >
                                <UInput
                                    required
                                    class="w-full"
                                    v-model="customLinkForm.label"
                                    size="sm"
                                />
                            </UFormField>
                            <UFormField
                                required
                                label="URL"
                            >
                                <UInput
                                    required
                                    type="text"
                                    placeholder="https://..."
                                    class="w-full"
                                    v-model="customLinkForm.url"
                                    size="sm"
                                />
                            </UFormField>
                            <UFormField label="Target">
                                <USelect
                                    required
                                    class="w-full"
                                    :items="customLinkTargetOptions"
                                    v-model="customLinkForm.target"
                                    size="sm"
                                />
                            </UFormField>

                            <div class="flex items-center justify-end">
                                <UButton
                                    type="submit"
                                    size="sm"
                                    :disabled="!customLinkForm.isDirty"
                                    variant="outline"
                                >
                                    Add to menu
                                </UButton>
                            </div>
                        </form>
                    </template>
                </UAccordion>
            </div>
            <div class="col-span-3">
                <UCard :ui="{
                    body: 'p-2 sm:p-2'
                }">
                    <UTree
                        v-model:expanded="expandedItems"
                        :ui="{
                            link: 'before:bg-transparent hover:not-disabled:before:bg-transparent px-1 py-1',
                        }"
                        multiple
                        color="neutral"
                        value-key="id"
                        :items="form.items"
                    >
                        <template #item="{ item, index, level, expanded, selected }">
                            <MenuTreeItem
                                @remove="handleRemove"
                                :item="item"
                                :index="index"
                                :level="level"
                                :parent-item="(findParentFromId(form.items, item.id) as Modules.Menu.Data.MenuItemData)"
                                v-model="expandedItems"
                                :expanded="expanded"
                                :selected="selected"
                                class="focus:ring-primary flex items-center rounded outline-none focus:ring-2"
                            />
                        </template>
                    </UTree>

                    <template #footer>
                        <div class="flex items-center justify-end">
                            <UButton
                                @click.prevent="submit"
                                type="button"
                                :disabled="!form.isDirty"
                                :loading="form.processing"
                            >
                                Save menu
                            </UButton>
                        </div>
                    </template>
                </UCard>
            </div>
        </div>
    </BaseModalPage>
</template>

<script setup lang="ts">
import {
    type Instruction,
    extractInstruction,
} from '@atlaskit/pragmatic-drag-and-drop-hitbox/tree-item';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { monitorForElements } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { type AccordionItem } from '@nuxt/ui';
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';
import { useTree } from '@modules/Builder/resources/scripts/use-tree';
import { extractIds, findParentFromId, removeElement } from '@modules/Builder/resources/scripts/factory';
import MenuTreeItem from '@modules/Menu/resources/components/menu-tree-item.vue';
import BaseModalPage from '@/components/BaseModalPage.vue';
import { getId } from '@/helpers';

const { menu, pages, posts } = defineProps<{
    menu: Modules.Menu.Data.MenuData;
    pages: Modules.Page.Data.PageData[];
    posts: Modules.Page.Data.PostData[];
}>();

const modalRef = ref<InstanceType<typeof BaseModalPage> | null>(null);
const showModal = ref(true);

const {
    updateTree,
    customLinkTargetOptions,
} = useTree();

type TForm = {
    id: string;
    name: string;
    deleted: string[];
    items: Modules.Menu.Data.MenuItemData[];
};

const form = useForm<TForm>({
    id: menu.id,
    name: menu.name,
    deleted: [],
    items: menu.items,
});

const customLinkForm = useForm({
    url: '',
    label: '',
    target: '_blank',
});

const expandedItems = ref(extractIds(form.items));

watch(
    () => menu,
    () => {
        expandedItems.value = extractIds(form.items);
        form.items = menu.items;
    },
);

function submit() {
    form.put(route('admin.menus.update', { menu: menu.id }), {
        onSuccess: () => {
            modalRef.value?.reload({ only: ['menu'] })
            form.items = menu.items
            useToast().add({ title: 'Menu updated', color: 'success' })
        },
        onError: (e) => {
            console.log(e);
            useToast().add({ title: 'Error updating menu.', color: 'error' })
        },
    });
}

function handleRemove(event: Modules.Menu.Data.MenuItemData) {
    if (!event) return;
    if (!event.isRecent) {
        form.deleted.push(event.id);
    }
    const updated = removeElement(form.items, event.id);
    form.items = updated as Modules.Menu.Data.MenuItemData[];
}

function addPageToMenu(
    page: Modules.Page.Data.PageData | Modules.Page.Data.PostData,
    type: Modules.Menu.Enums.MenuItemType,
) {
    form.items = [
        ...form.items,
        {
            id: getId(),
            isRecent: true,
            parentId: null,
            menuId: menu.id,
            type: type,
            path: page.id,
            label: page.title,
            target: '_self',
            defaultOpen: true,
            to: page.url,
            href: page.url,
            children: [],
        },
    ];
}

function addCustomLinkToMenu() {
    form.items = [
        ...form.items,
        {
            id: getId(),
            isRecent: true,
            parentId: null,
            menuId: menu.id,
            type: 'custom',
            path: customLinkForm.url,
            label: customLinkForm.label,
            target: customLinkForm.target,
            defaultOpen: true,
            to: '#',
            href: '#',
            children: [],
        },
    ];
    customLinkForm.reset();
}

watchEffect((onCleanup) => {
    const dndFunction = combine(
        monitorForElements({
            onDrop(args) {
                const { location, source } = args;
                // didn't drop on anything
                if (!location.current.dropTargets.length) return;

                const itemId = source.data.id as string;
                const target = location.current.dropTargets[0];
                const targetId = target.data.id as string;

                const instruction: Instruction | null = extractInstruction(
                    target.data,
                );

                if (instruction !== null) {
                    form.items = (updateTree(form.items, {
                        type: 'instruction',
                        instruction,
                        itemId,
                        targetId,
                    }) ?? []) as Modules.Menu.Data.MenuItemData[];
                }
            },
        }),
    );

    onCleanup(() => {
        dndFunction();
    });
});

const accordionItems: AccordionItem[] = [
    {
        label: 'Pages',
        icon: 'ph:book-open-text',
        slot: 'pages',
    },
    {
        label: 'Posts',
        icon: 'ph:push-pin',
        slot: 'posts',
    },
    {
        label: 'Custom link',
        icon: 'ph:link',
        slot: 'custom',
    },
];


onMounted(() => {
    console.log(pages)
    console.log(posts)
})
</script>

<style scoped></style>
