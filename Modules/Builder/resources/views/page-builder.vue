<template>
    <UApp>
        <div class="size-screen flex h-screen flex-col bg-[#404040]">
            <header
                class="bg-neutral-900 sticky top-0 flex h-12 shrink-0 items-center gap-x-4 border-b border-neutral-700 px-4">
                <div
                    class="flex flex-1 items-center justify-between gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex w-1/3 items-center gap-2">
                        <UButton
                            target="_self"
                            label="Exit"
                            size="xs"
                            :href="
                                type == 'page'
                                    ? route('admin.pages.index')
                                    : type == 'post'
                                      ? route('admin.posts.index')
                                      : route('admin.layouts.index')
                            "
                            color="warning"
                            icon="ph:arrow-left"
                            variant="soft" />
                        <div
                            class="flex items-center gap-1 font-bold text-white">
                            <template v-if="store.builderType == 'layout'">
                                | &nbsp;&nbsp;Layout: {{ editable.name }}
                            </template>
                            <template v-else>
                                | &nbsp;&nbsp;{{ editable.title }}
                                <UBadge
                                    variant="subtle"
                                    class="dark"
                                    size="sm"
                                    :color="editable.statusColor"
                                    :label="editable.status" />
                            </template>
                        </div>
                        <UButton
                            size="xs"
                            v-if="type == 'layout'"
                            color="primary"
                            icon="ph:note-pencil"
                            variant="solid"
                            label="Edit"
                            @click="
                                visitModal(
                                    route('admin.layouts.meta.edit', [
                                        editable.id,
                                    ]),
                                )
                            " />

                        <UButton
                            size="xs"
                            v-else
                            color="primary"
                            icon="ph:note-pencil"
                            variant="solid"
                            label="Edit"
                            @click="
                                visitModal(
                                    type == 'page'
                                        ? route(
                                              'admin.pages.meta.edit',
                                              editable.id,
                                          )
                                        : route(
                                              'admin.posts.meta.edit',
                                              editable.id,
                                          ),
                                )
                            " />
                    </div>
                    <div class="flex w-1/3 items-center justify-center gap-1">
                        <BaseTooltip
                            v-for="device in DEVICES"
                            :key="device.value"
                            :content="device.label">
                            <UButton
                                @click.prevent="
                                    store.changeDevice(
                                        device.value as DeviceType,
                                    )
                                "
                                size="sm"
                                color="primary"
                                :variant="
                                    device.value == selectedDevice!.value
                                        ? 'solid'
                                        : 'soft'
                                "
                                :icon="device!.icon" />
                        </BaseTooltip>
                    </div>
                    <div class="flex w-1/3 items-center justify-end gap-2">
                        <UButtonGroup>
                            <BaseTooltip content="Zoom out">
                                <UButton
                                    size="xs"
                                    variant="subtle"
                                    class="text-white"
                                    :disabled="!canZoomOut"
                                    @click.prevent="zoomOut"
                                    icon="ph:magnifying-glass-minus" />
                            </BaseTooltip>

                            <UButton
                                size="xs"
                                variant="ghost"
                                class="pointer-events-none border border-neutral-700 text-white"
                                disabled
                                :label="currentZoom" />

                            <BaseTooltip content="Zoom in">
                                <UButton
                                    size="xs"
                                    variant="subtle"
                                    class="text-white"
                                    :disabled="!canZoomIn"
                                    @click.prevent="zoomIn"
                                    icon="ph:magnifying-glass-plus" />
                            </BaseTooltip>
                        </UButtonGroup>
                        <BaseTooltip content="Reset view">
                            <UButton
                                size="xs"
                                variant="subtle"
                                class="text-white"
                                @click.prevent="resetView"
                                icon="ph:crosshair" />
                        </BaseTooltip>
                        <BaseTooltip content="Undo">
                            <UButton
                                :disabled="!store.history.canUndo"
                                @click.prevent="store.history.undo()"
                                variant="subtle"
                                class="text-white"
                                size="sm"
                                icon="ph:arrow-arc-left" />
                        </BaseTooltip>
                        <BaseTooltip content="Redo">
                            <UButton
                                :disabled="!store.history.canRedo"
                                @click.prevent="store.history.redo()"
                                variant="subtle"
                                class="text-white"
                                size="sm"
                                icon="ph:arrow-arc-right" />
                        </BaseTooltip>

                        <UButton
                            v-if="type != 'layout'"
                            size="sm"
                            variant="subtle"
                            class="text-white"
                            as-child>
                            <a
                                :href="route('pages.preview', [editable.id])"
                                target="_blank">
                                <UIcon name="ph:eye" />
                                Preview
                            </a>
                        </UButton>

                        <template v-if="type == 'layout'">
                            <UButton
                                :disabled="store.history.history?.length < 2"
                                @click.prevent="save"
                                :loading="isSaving"
                                color="primary"
                                label="Save" />
                        </template>
                        <template v-else>
                            <UButton
                                :disabled="store.history.history?.length < 2"
                                @click.prevent="save"
                                :loading="isSaving"
                                color="warning"
                                label="Save Draft" />
                            <UButton
                                :disabled="
                                    store.history.history?.length < 2 &&
                                    !editable.isDifferentFromPublishedVersion
                                "
                                @click.prevent="saveAndPublishPage"
                                :loading="isSaving"
                                color="success"
                                label="Save & Publish" />
                        </template>
                    </div>
                </div>
            </header>

            <BaseLoader v-if="isLoading" />

            <main
                :class="{
                    'ps-56': showComponentsPanel,
                    'pe-56': showSettingsPanel,
                }"
                class="relative flex-1 flex-col overflow-x-hidden transition-all duration-300">
                <!-- @vue-expect-error: spread props -->
                <VueZoomable
                    ref="zoomRef"
                    style="width: 100%; height: 100%"
                    v-model:zoom="zoomLevel"
                    v-model:pan="panLevel"
                    selector="#pageBuilder"
                    v-bind="zoomOptions">
                    <div
                        id="pageBuilder"
                        class="@container relative mx-auto min-h-full transition-all duration-300"
                        :class="{
                            'opacity-0': isLoading,
                            'opacity-100': !isLoading,
                            '!w-[768px]': store.isTablet,
                            '!w-[447px]': store.isMobile,
                            '!w-full !min-w-[1024px]': store.isDesktop,
                        }">
                        <template v-if="store.builderType == 'layout'">
                            <BaseRecursiveElement
                                v-for="element in store.elements"
                                :key="element.id"
                                :element="element" />
                        </template>

                        <template
                            v-if="
                                !!layout &&
                                (store.builderType == 'page' ||
                                    store.builderType == 'post')
                            ">
                            <BaseRecursiveElement
                                v-for="layoutElement in store.renderableElements"
                                :key="layoutElement.id"
                                :element="layoutElement" />
                        </template>
                    </div>
                </VueZoomable>
                <button
                    v-if="!isLive"
                    @click="showComponentsPanel = !showComponentsPanel"
                    :class="showComponentsPanel ? 'left-56' : 'left-0'"
                    class="fixed top-1/2 h-12 cursor-pointer rounded-r border border-l-0 border-neutral-700 bg-neutral-800 text-neutral-200 transition-[left,right,width] duration-200 ease-linear">
                    <UIcon
                        name="ph:caret-left-fill"
                        class="transition-all duration-300"
                        :class="{ 'rotate-180': !showComponentsPanel }" />
                </button>

                <button
                    v-if="!isLive"
                    @click="showSettingsPanel = !showSettingsPanel"
                    :class="showSettingsPanel ? 'right-60' : 'right-0'"
                    class="fixed top-1/2 right-0 h-12 cursor-pointer rounded-l border border-l-0 border-neutral-700 bg-neutral-800 text-neutral-200 transition-[left,right,width] duration-200 ease-linear">
                    <UIcon
                        name="ph:caret-right-fill"
                        class="transition-all duration-300"
                        :class="{ 'rotate-180': !showSettingsPanel }" />
                </button>
            </main>
        </div>

        <aside
            :class="showComponentsPanel ? 'w-64' : 'w-0'"
            class="fixed top-12 bottom-0 left-0 overflow-hidden border-r border-neutral-700 bg-neutral-900 transition-[left,right,width] duration-200 ease-linear">
            <UCard
                variant="solid"
                :ui="{
                    root: 'size-full rounded-none bg-neutral-900',
                    body: 'p-0 sm:p-0 flex flex-col size-full',
                }">
                <div
                    class="flex size-full flex-1 flex-col divide-y divide-neutral-700">
                    <div class="relative flex h-1/2 flex-col">
                        <div
                            class="dark flex-1 overflow-y-auto text-sm text-white">
                            <UTabs
                                :ui="{
                                    list: 'justify-between',
                                }"
                                variant="pill"
                                size="sm"
                                :items="tabs"
                                class="w-full">
                                <template #elements>
                                    <div class="px-2">
                                        <ZioraElementsTab />
                                    </div>
                                </template>
                                <template #blocks>
                                    <BuilderBlocksTab />
                                </template>
                                <template #globals>
                                    <BuilderGlobalsTab />
                                </template>
                            </UTabs>
                        </div>
                    </div>

                    <div class="relative flex h-1/2 flex-col">
                        <div
                            class="flex h-8 items-center gap-2 border-b border-neutral-700 bg-neutral-800 p-2 text-sm font-medium text-white">
                            <UIcon name="ph:stack-fill" />
                            <h2>Structure</h2>
                        </div>
                        <div
                            class="flex-1 overflow-y-auto ps-2 pe-1 text-sm text-white">
                            <ElementTree />
                        </div>
                    </div>
                </div>
            </UCard>
        </aside>

        <aside
            :class="showSettingsPanel ? 'w-60' : 'w-0'"
            class="_w-60 fixed top-12 right-0 bottom-0 overflow-hidden border-l border-neutral-700 bg-neutral-900 transition-[left,right,width] duration-200 ease-linear">
            <UCard
                variant="solid"
                :ui="{
                    root: 'size-full rounded-none overflow-auto bg-neutral-900',
                    body: 'p-0 sm:p-0 flex flex-col',
                }">
                <div class="flex size-full flex-1 flex-col">
                    <div
                        class="flex h-8 items-center justify-between gap-2 border-b border-neutral-700 bg-neutral-800 p-2 text-sm font-medium text-white">
                        <div class="flex items-center gap-1">
                            <UIcon
                                name="ph:sliders"
                                class="rotate-90" />
                            <h2>Settings</h2>
                        </div>
                        <div v-if="!!store.selectedElement">
                            <BaseTooltip content="Clear selection">
                                <UButton
                                    @click="store.clearSelectedElement()"
                                    size="xs"
                                    variant="soft"
                                    color="warning"
                                    icon="ph:arrow-left" />
                            </BaseTooltip>
                        </div>
                    </div>
                    <div class="flex-1 ps-2 pe-1 text-sm text-white">
                        <BuilderElementSettings
                            :key="store.selectedElement?.id" />
                    </div>
                </div>
            </UCard>
        </aside>
        
        <CommandPalette v-model:open="showCommandPalette" />
    </UApp>
</template>

<script setup lang="ts">
import { useNotification } from '@/composables/use-notification';
import { triggerPostMoveFlash } from '@atlaskit/pragmatic-drag-and-drop-flourish/trigger-post-move-flash';
import { extractClosestEdge } from '@atlaskit/pragmatic-drag-and-drop-hitbox/closest-edge';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { monitorForElements } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { router } from '@inertiajs/vue3';
import { visitModal } from '@inertiaui/modal-vue';
import BaseLoader from '@modules/Builder/resources/components/base-loader.vue';
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';
import ElementTree from '@modules/Builder/resources/components/element-tree/element-tree.vue';
import BuilderBlocksTab from '@modules/Builder/resources/components/layout/builder-blocks-tab.vue';
import BuilderElementSettings from '@modules/Builder/resources/components/layout/builder-element-settings-tab.vue';
import ZioraElementsTab from '@modules/Builder/resources/components/layout/builder-elements-tab.vue';
import BuilderGlobalsTab from '@modules/Builder/resources/components/layout/builder-globals-tab.vue';
import CommandPalette from '@modules/Builder/resources/components/layout/command-palette.vue';
import { DEVICES } from '@modules/Builder/resources/scripts/constants';
import {
    DeviceType,
    InsertLocation,
} from '@modules/Builder/resources/scripts/enums';
import { extractStyles } from '@modules/Builder/resources/scripts/factory';
import {
    TBuilderType,
    TElement,
} from '@modules/Builder/resources/scripts/types';
import { useZiora } from '@modules/Builder/resources/scripts/use-ziora';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';
import { TabsItem } from '@nuxt/ui';
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js';
import { useHead } from '@unhead/vue';
import VueZoomable from 'vue-zoomable';
import 'vue-zoomable/dist/style.css';

useNotification();

const initialPan = {
    x: 0,
    y: 0,
    deltaX: 0,
    deltaY: 0,
};
const {
    editable,
    type = 'page',
    layout,
    isLive = false,
} = defineProps<{
    editable:
        | Modules.Page.Data.PageData
        | Modules.Page.Data.PostData
        | Modules.Layout.Data.LayoutData;
    type?: TBuilderType;
    layout?: Modules.Layout.Data.LayoutData | null;
    isLive?: boolean;
}>();

const isLoading = ref<boolean>(true);
const store = useZiora();
const toast = useToast();
const zoomLevel = ref(0.92);
const panLevel = ref(initialPan);
const showComponentsPanel = ref(true);
const showSettingsPanel = ref(true);
const showCommandPalette = ref(false);

const tabs = [
    {
        label: 'Elements',
        icon: 'ph:cube',
        slot: 'elements' as const,
    },
    {
        label: 'Blocks',
        icon: 'ph:cube',
        slot: 'blocks' as const,
    },
    {
        label: 'Globals',
        icon: 'ph:palette',
        slot: 'globals' as const,
    },
] satisfies TabsItem[];

// Memoize style extraction for performance
const computedLayoutStyles = computed(() =>
    extractStyles(store.layoutElements || []),
);
const computedEditorStyles = computed(() =>
    extractStyles(store.editorElements),
);
const computedGlobalThemeStyles = computed(() => {
    const body = store.elements[0];
    if (!body || !body.props?.theme) {
        return `
            :root {
                --color-primary: #3b82f6;
                --color-background: #ffffff;
                --font-family: 'Inter', sans-serif;
                --border-radius: 8px;
            }
        `;
    }
    const theme = body.props.theme;
    return `
        :root {
            --color-primary: ${theme.primaryColor || '#3b82f6'};
            --color-background: ${theme.backgroundColor || '#ffffff'};
            --font-family: ${theme.fontFamily || 'Inter, sans-serif'};
            --border-radius: ${theme.borderRadius !== undefined ? theme.borderRadius : 8}px;
        }
    `;
});

useHead({
    style: [
        {
            textContent: computedGlobalThemeStyles,
            id: 'global_theme_styles',
        },
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

const zoomOptions = {
    minZoom: 0.3,
    maxZoom: 2,
    initialZoom: 0.8,
    panEnabled: true,
    initialPanX: 0,
    initialPanY: 0,
    enableControlButton: false,
    wheelEnabled: true,
    touchEnabled: false,
    zoomOrigin: 'pointer',
    dblClickZoomStep: 0.1,
    buttonZoomStep: 0.1,
};

const currentZoom = computed(() => (zoomLevel.value * 100).toFixed(0) + '%');
const canZoomOut = computed(() => zoomLevel.value > zoomOptions.minZoom);
const canZoomIn = computed(() => zoomLevel.value < zoomOptions.maxZoom);

function resetView() {
    zoomLevel.value = zoomOptions.initialZoom;
    panLevel.value = initialPan;
}

function zoomIn() {
    zoomLevel.value = Math.min(
        zoomLevel.value + zoomOptions.dblClickZoomStep,
        zoomOptions.maxZoom,
    );
}

function zoomOut() {
    zoomLevel.value = Math.max(
        zoomLevel.value - zoomOptions.dblClickZoomStep,
        zoomOptions.minZoom,
    );
}

const selectedDevice = computed(
    () => DEVICES.find((d: any) => d.value == store.device) || DEVICES[0],
);

watch(
    () => store.selectedElement,
    (newVal) => {
        if (!!newVal) {
            store.selectedTab = 'styling';
        }
    },
);

const isSaving = ref(false);

function save() {
    isSaving.value = true;
    switch (type) {
        case 'page':
            savePage();
            break;
        case 'layout':
            saveLayout();
            break;
        case 'post':
            savePost();
        default:
            break;
    }
}

function saveAndPublishPage() {
    isSaving.value = true;
    router.put(
        route('admin.pages.publish', [editable.id]),
        {
            content: store.elements as any,
        },
        {
            preserveScroll: true,
            onSuccess: () => store.history.clear(),
            onError: () =>
                toast.add({ title: 'Error encountered', color: 'error' }),
            onFinish: () => (isSaving.value = false),
        },
    );
}

function saveLayout() {
    router.put(
        route('admin.layouts.update', [editable.id]),
        {
            content: store.elements as any,
        },
        {
            preserveScroll: true,
            onSuccess: () => store.history.clear(),
            onError: () =>
                toast.add({ title: 'Error encountered', color: 'error' }),
            onFinish: () => (isSaving.value = false),
        },
    );
}

function savePage() {
    router.put(
        route('admin.pages.content', [editable.id]),
        {
            content: store.elements as any,
        },
        {
            preserveScroll: true,
            onSuccess: () => store.history.clear(),
            onError: () =>
                toast.add({ title: 'Error encountered', color: 'error' }),
            onFinish: () => (isSaving.value = false),
        },
    );
}

function savePost() {
    router.put(
        route('admin.posts.content', [editable.id]),
        {
            content: store.elements as any,
        },
        {
            preserveScroll: true,
            onSuccess: () => store.history.clear(),
            onError: () =>
                toast.add({ title: 'Error encountered', color: 'error' }),
            onFinish: () => (isSaving.value = false),
        },
    );
}

onMounted(() => {
    store.history.clear();
});

onBeforeMount(() => {
    const pageContent = editable.content!.map((item: TElement) =>
        ZioraElement.fromObject(item),
    );
    if (!!layout) {
        const layoutContent = layout.content!.map((item: TElement) =>
            ZioraElement.fromObject(item),
        );
        store.setLayoutElements(layoutContent);
    }
    store.setInitialElements(pageContent, type);
});

watchEffect((onCleanup) => {
    const dndFunction = combine(
        monitorForElements({
            canMonitor({ source }) {
                return source.data?.draggableType !== 'TREE';
            },
            onDrop({ source, location }) {
                const target = location.current.dropTargets[0];
                if (!target) {
                    return;
                }
                const targetData = target.data;
                const sourceData = source.data;

                // determine where to insert / add
                const showingIndicator = targetData.showingIndicator;

                if (!showingIndicator && !targetData.canDrop) {
                    return;
                }

                let insertAt: InsertLocation | null = null;
                if (showingIndicator) {
                    const closestEdge = extractClosestEdge(targetData);
                    if (closestEdge == 'top' || closestEdge == 'left') {
                        insertAt = InsertLocation.Before;
                    } else {
                        insertAt = InsertLocation.After;
                    }
                }

                const targetParentId: string = targetData.parentId as string;
                const action = sourceData?.action || 'move';
                const item: ZioraElement = sourceData.item as ZioraElement;

                if (action == 'add') {
                    store.addNewElement(targetParentId, item, insertAt);
                } else {
                    store.moveElement(item.id, targetParentId, insertAt);
                }

                const element: HTMLElement | null = document.querySelector(
                    `[data-element-id="${item.id}"]`,
                );

                if (element instanceof HTMLElement) {
                    triggerPostMoveFlash(element);
                }
            },
        }),
    );

    setTimeout(() => (isLoading.value = false), 500);

    onCleanup(() => {
        dndFunction();
    });
});

defineShortcuts({
    meta_c: () => {
        if (!!store.selectedElement) {
            store.cutOrCopyElement(store.selectedElement, 'copy');
        }
    },
    meta_x: () => {
        if (!!store.selectedElement && !store.selectedElement.isRootElement()) {
            store.cutOrCopyElement(store.selectedElement, 'cut');
        }
    },
    meta_v: () => {
        if (!!store.selectedElement) {
            store.pasteElement(store.selectedElement);
        }
    },
    meta_d: () => {
        if (!!store.selectedElement && !store.selectedElement.isRootElement()) {
            store.duplicateElement(store.selectedElement);
        }
    },
    meta_shift_d: () => {
        if (!!store.selectedElement && !store.selectedElement.isRootElement()) {
            store.deleteElement(store.selectedElement.id);
        }
    },
    escape: () => {
        store.clearSelectedElement();
    },
    meta_z: () => {
        if (store.history.canUndo) {
            store.history?.undo();
        }
    },
    meta_y: () => {
        if (store.history.canRedo) {
            store.history?.redo();
        }
    },
    meta_k: () => {
        showCommandPalette.value = !showCommandPalette.value;
    },
});
</script>

<style scoped>
/* Ensure all text in side panels is white and readable */
:deep(.dark) {
    color: white !important;
}

:deep(.dark *) {
    color: white !important;
}

:deep(.dark label),
:deep(.dark h1),
:deep(.dark h2),
:deep(.dark h3),
:deep(.dark h4),
:deep(.dark h5),
:deep(.dark h6) {
    color: white !important;
}

:deep(.dark input),
:deep(.dark textarea),
:deep(.dark select) {
    color: white !important;
    background-color: #262626 !important;
    border-color: #404040 !important;
}

:deep(.dark input::placeholder) {
    color: #999999 !important;
}
</style>
