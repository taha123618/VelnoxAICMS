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
                        <div class="flex items-center gap-2 font-bold text-white">
                            <template v-if="store.builderType == 'layout'">
                                | &nbsp;&nbsp;Layout: {{ (editable as any).name }}
                            </template>
                            <template v-else>
                                | &nbsp;&nbsp;{{ (editable as any).title }}
                                <UBadge
                                    variant="subtle"
                                    class="dark"
                                    size="sm"
                                    :color="(editable as any).statusColor"
                                    :label="(editable as any).status" />
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
                    <div class="flex w-1/3 items-center justify-end gap-4">
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

                        <BaseTooltip content="Version History" v-if="type != 'layout'">
                            <UButton
                                size="sm"
                                variant="soft"
                                color="neutral"
                                @click.prevent="() => { showHistoryModal = true; }"
                                icon="ph:clock-counter-clockwise"
                                label="History" />
                        </BaseTooltip>

                        <BaseTooltip content="Generate with AI">
                            <UButton
                                size="sm"
                                variant="soft"
                                color="info"
                                @click.prevent="() => { showAiModal = true; }"
                                icon="ph:magic-wand"
                                label="AI Assistant" />
                        </BaseTooltip>

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
                                    !(editable as any).isDifferentFromPublishedVersion
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

                        <!-- Modern AI Section Generating Skeleton Placeholder -->
                        <div v-if="aiJobId && (aiJobStatus === 'queued' || aiJobStatus === 'processing')" class="relative my-6 p-6 rounded-2xl border-2 border-dashed border-info-500/40 bg-neutral-900/80 backdrop-blur-md shadow-2xl overflow-hidden animate-pulse">
                            <div class="absolute inset-0 bg-gradient-to-r from-info-500/10 via-primary-500/10 to-info-500/10"></div>
                            <div class="relative z-10 space-y-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-info-500/20 text-info-400 ring-1 ring-info-500/30">
                                            <UIcon name="ph:sparkle-duotone" class="w-5 h-5 animate-spin text-info-400" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                                Generating AI Section...
                                                <UBadge color="info" variant="subtle" size="xs">{{ aiJobProgress > 0 ? aiJobProgress + '%' : 'Queued' }}</UBadge>
                                            </h4>
                                            <p class="text-xs text-neutral-400">Constructing layout, structure, and responsive styles in background</p>
                                        </div>
                                    </div>
                                    <UButton size="xs" color="error" variant="ghost" icon="ph:x" @click="cancelAiJob">Cancel</UButton>
                                </div>
                                <!-- Skeleton Card & Line Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="h-32 rounded-xl bg-neutral-800/80 border border-neutral-700/50 p-4 space-y-3">
                                        <div class="h-4 w-3/4 bg-neutral-700/60 rounded-md"></div>
                                        <div class="h-3 w-1/2 bg-neutral-700/40 rounded-md"></div>
                                        <div class="h-8 w-full bg-neutral-700/30 rounded-lg mt-4"></div>
                                    </div>
                                    <div class="h-32 rounded-xl bg-neutral-800/80 border border-neutral-700/50 p-4 space-y-3">
                                        <div class="h-4 w-2/3 bg-neutral-700/60 rounded-md"></div>
                                        <div class="h-3 w-1/2 bg-neutral-700/40 rounded-md"></div>
                                        <div class="h-8 w-full bg-neutral-700/30 rounded-lg mt-4"></div>
                                    </div>
                                    <div class="h-32 rounded-xl bg-neutral-800/80 border border-neutral-700/50 p-4 space-y-3">
                                        <div class="h-4 w-4/5 bg-neutral-700/60 rounded-md"></div>
                                        <div class="h-3 w-1/2 bg-neutral-700/40 rounded-md"></div>
                                        <div class="h-8 w-full bg-neutral-700/30 rounded-lg mt-4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="store.elements.length === 0 && !aiJobId" class="flex flex-col items-center justify-center h-[calc(100vh-120px)] text-neutral-400 gap-4 border-2 border-dashed border-neutral-700/50 hover:border-neutral-600 transition-colors rounded-2xl m-6 bg-neutral-800/20 backdrop-blur-sm">
                            <div class="flex items-center justify-center w-20 h-20 rounded-full bg-neutral-800 border border-neutral-700 shadow-inner mb-2">
                                <UIcon name="ph:layout-duotone" class="w-10 h-10 text-neutral-400" />
                            </div>
                            <div class="text-center">
                                <h3 class="text-xl font-bold text-white mb-2 tracking-tight">Your Canvas is Empty</h3>
                                <p class="text-sm text-neutral-400 max-w-sm mx-auto leading-relaxed">
                                    Drag elements from the left sidebar to start building your layout manually, or click 
                                    <button @click.prevent="showAiModal = true" class="text-info-400 hover:text-info-300 font-semibold underline underline-offset-4 transition-colors mx-1">Generate with AI</button> 
                                    to create it instantly.
                                </p>
                            </div>
                        </div>
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
            :class="showComponentsPanel ? 'w-56' : 'w-0'"
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
                                        <VelnoxAIElementsTab />
                                    </div>
                                </template>
                                <template #blocks>
                                    <BuilderBlocksTab />
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

        <!-- Modals now inside UApp -->
        <UModal v-model:open="showAiModal" title="AI Section Generator" description="Describe the section you want to create in natural language." class="z-50 backdrop-blur-sm">
            <template #content>
            <VisuallyHidden>
                <DialogTitle>AI Section Generator</DialogTitle>
                <DialogDescription>Describe the section you want to create in natural language.</DialogDescription>
            </VisuallyHidden>
            <UCard class="border border-neutral-800 shadow-2xl rounded-xl overflow-hidden bg-neutral-900/95">
                <template #header>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-info-500/20 text-info-400 ring-1 ring-info-500/30">
                            <UIcon name="ph:magic-wand-duotone" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white bg-clip-text text-transparent bg-gradient-to-r from-info-400 to-primary-400">AI Section Generator</h3>
                            <p class="text-xs text-neutral-400 mt-0.5">Powered by advanced LLM models</p>
                        </div>
                    </div>
                </template>
                <div class="space-y-4">
                    <p class="text-sm text-neutral-300 leading-relaxed">
                        Describe the section you want to create in natural language. The AI will build the structure, apply styles, and populate content asynchronously.
                    </p>
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-info-500 to-primary-500 rounded-lg blur opacity-20 group-focus-within:opacity-40 transition duration-500"></div>
                        <UTextarea
                            v-model="aiPrompt"
                            :disabled="isGeneratingAi"
                            color="info"
                            variant="outline"
                            placeholder="e.g. A three column pricing table for a SaaS product with a highlighted pro plan..."
                            autoresize
                            class="relative w-full" />
                    </div>

                    <!-- Live Progress & Status Bar -->
                    <div v-if="aiJobStatus !== 'idle'" class="p-3 bg-neutral-800/80 rounded-lg border border-neutral-700 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium capitalize text-neutral-300 flex items-center gap-1.5">
                                <UIcon v-if="aiJobStatus === 'queued' || aiJobStatus === 'processing'" name="ph:spinner" class="animate-spin text-info-400" />
                                <UIcon v-else-if="aiJobStatus === 'completed'" name="ph:check-circle" class="text-success-400" />
                                <UIcon v-else-if="aiJobStatus === 'failed'" name="ph:warning-circle" class="text-error-400" />
                                Status: {{ aiJobStatus }}
                            </span>
                            <span class="font-bold text-info-400">{{ aiJobProgress }}%</span>
                        </div>
                        <div class="w-full h-2 bg-neutral-700 rounded-full overflow-hidden">
                            <div
                                class="h-full bg-gradient-to-r from-info-500 to-primary-500 transition-all duration-300 ease-out"
                                :style="{ width: aiJobProgress + '%' }"
                            ></div>
                        </div>
                        <p v-if="aiJobError" class="text-xs text-error-400 font-mono mt-1">
                            Error: {{ aiJobError }}
                        </p>
                    </div>
                </div>
                <template #footer>
                    <div class="flex justify-end gap-2">
                        <UButton color="neutral" variant="ghost" :disabled="isGeneratingAi" @click.prevent="() => { showAiModal = false; }">Cancel</UButton>
                        <UButton v-if="aiJobStatus === 'failed'" color="warning" variant="subtle" @click="retryAiJob">Retry Job</UButton>
                        <UButton color="primary" :loading="isGeneratingAi" :disabled="isGeneratingAi || !aiPrompt" @click="generateAiSection">Generate</UButton>
                    </div>
                </template>
            </UCard>
            </template>
        </UModal>
        
        <UModal v-model:open="showHistoryModal" title="Version History" description="View and restore previous versions of your page layout." class="z-50">
            <template #content>
            <VisuallyHidden>
                <DialogTitle>Version History</DialogTitle>
                <DialogDescription>View and restore previous versions of your page layout.</DialogDescription>
            </VisuallyHidden>
            <UCard class="border border-neutral-800 shadow-2xl rounded-xl overflow-hidden bg-neutral-900/95">
                <template #header>
                    <div class="flex items-center gap-2">
                        <UIcon name="ph:clock-counter-clockwise" class="text-primary-500" />
                        <h3 class="text-base font-semibold leading-6 text-white">Version History</h3>
                    </div>
                </template>
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                        <p class="text-sm text-neutral-400">View and restore previous published versions of this page.</p>
                        
                        <div v-if="!pageVersions || pageVersions.length === 0" class="text-center py-8 text-neutral-500">
                            No previous versions found.
                    </div>
                    
                    <div v-else class="space-y-3">
                            <div v-for="version in pageVersions" :key="version.id" class="flex items-center justify-between p-3 bg-neutral-800 rounded-lg border border-neutral-700">
                            <div>
                                    <div class="text-sm font-medium text-white">{{ new Date(version.created_at).toLocaleString() }}</div>
                                    <div class="text-xs text-neutral-400">Version ID: {{ version.id }}</div>
                            </div>
                                <UButton 
                                    size="xs" 
                                    color="primary" 
                                    variant="soft" 
                                    @click="restoreVersion(version)"
                                    icon="ph:arrow-u-up-left"
                                    label="Restore" />
                        </div>
                    </div>
                </div>
                <template #footer>
                        <div class="flex justify-end gap-2">
                            <UButton color="neutral" variant="ghost" @click="() => { showHistoryModal = false; }">Close</UButton>
                    </div>
                </template>
            </UCard>
            </template>
        </UModal>
    </UApp>
</template>

<script setup lang="ts">
import { useNotification } from '@/composables/use-notification';
import { triggerPostMoveFlash } from '@atlaskit/pragmatic-drag-and-drop-flourish/trigger-post-move-flash';
import { extractClosestEdge } from '@atlaskit/pragmatic-drag-and-drop-hitbox/closest-edge';
import { combine } from '@atlaskit/pragmatic-drag-and-drop/combine';
import { monitorForElements } from '@atlaskit/pragmatic-drag-and-drop/element/adapter';
import { router, usePage } from '@inertiajs/vue3';
import { visitModal } from '@inertiaui/modal-vue';
import { DialogTitle, DialogDescription, VisuallyHidden } from 'reka-ui';
import axios from 'axios';
import { ref, computed, watch, onMounted, onUnmounted, onBeforeMount, watchEffect } from 'vue';
import { route } from 'ziggy-js';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

import BaseLoader from '@modules/Builder/resources/components/base-loader.vue';
import BaseRecursiveElement from '@modules/Builder/resources/components/base-recursive-element.vue';
import BaseTooltip from '@modules/Builder/resources/components/base-tooltip.vue';
import ElementTree from '@modules/Builder/resources/components/element-tree/element-tree.vue';
import BuilderBlocksTab from '@modules/Builder/resources/components/layout/builder-blocks-tab.vue';
import BuilderElementSettings from '@modules/Builder/resources/components/layout/builder-element-settings-tab.vue';
import VelnoxAIElementsTab from '@modules/Builder/resources/components/layout/builder-elements-tab.vue';
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
import { useVelnoxAI } from '@modules/Builder/resources/scripts/use-VelnoxAI';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
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
const store = useVelnoxAI();
const toast = useToast();
const zoomLevel = ref(0.92);
const panLevel = ref(initialPan);
const showComponentsPanel = ref(true);
const showSettingsPanel = ref(true);

const showAiModal = ref<boolean>(false);
const showHistoryModal = ref<boolean>(false);
const pageVersions = ref<any[]>([]);

onMounted(async () => {
    if (type !== 'layout' && editable?.id) {
        try {
            const response = await axios.get(route('api.builder.pages.show', editable.id));
            if (response.data?.data?.versions) {
                pageVersions.value = response.data.data.versions;
            }
        } catch (e) {
            console.error('Failed to load page versions', e);
        }
    }
});

function restoreVersion(version: any) {
    if (!version || !version.content) return;
    
    // Parse the AST content from the version
    const parsedContent = version.content.map((item: any) => VelnoxAIElement.fromObject(item));
    store.setElements(parsedContent);
    
    toast.add({ title: 'Version restored!', description: 'Click Save to persist changes.', color: 'success' });
    showHistoryModal.value = false;
}
const aiPrompt = ref<string>('');
const isGeneratingAi = ref<boolean>(false);
const aiJobId = ref<string | null>(null);
const aiJobStatus = ref<string>('idle');
const aiJobProgress = ref<number>(0);
const aiJobError = ref<string | null>(null);

function resetAiJob() {
    aiJobId.value = null;
    aiJobStatus.value = 'idle';
    aiJobProgress.value = 0;
    aiJobError.value = null;
}

const transformAiElement = (el: any): any => {
    if (!el) return el;
    
    const props = el.props ? { ...el.props } : {};
    let content = props.content || {};
    if (typeof content !== 'object') {
        content = { innerText: String(content) };
    }
    const styles = props.styles || {};
    if (!styles.desktop) styles.desktop = { default: {}, hover: {}, active: {} };
    if (!styles.desktop.default) styles.desktop.default = {};
    if (!styles.tablet) styles.tablet = { default: {}, hover: {}, active: {} };
    if (!styles.mobile) styles.mobile = { default: {}, hover: {}, active: {} };
    if (!styles.custom) styles.custom = {};

    // Move flat style properties to desktop.default but preserve structural props
    const structuralProps = ['content', 'styles', 'tag', 'src', 'href', 'target', 'placeholder', 'type', 'name', 'value', 'options'];
    Object.keys(props).forEach(key => {
        if (!structuralProps.includes(key)) {
            styles.desktop.default[key] = props[key];
            delete props[key];
        }
    });
    
    props.content = content;
    props.styles = styles;

    // Ensure fallback tags for basic typographic elements
    if (el.type === 'heading' && !props.tag) props.tag = 'h2';
    if (el.type === 'paragraph' && !props.tag) props.tag = 'p';
    if (el.type === 'link' && !props.tag) props.tag = 'a';
    if (el.type === 'button' && !props.tag) props.tag = 'button';

    return {
        ...el,
        props,
        children: Array.isArray(el.children) ? el.children.map(transformAiElement) : []
    };
};

function handleAiJobUpdate(e: any) {
    if (aiJobId.value && e.jobId && e.jobId !== aiJobId.value) return;

    aiJobStatus.value = e.status;
    aiJobProgress.value = e.progress || 0;

    if (e.progress === 80 || (e.status === 'processing' && e.progress >= 80 && e.progress < 100)) {
        toast.add({
            title: 'AI Generation 80% Complete',
            description: 'Finalizing layout components and styles...',
            color: 'info',
            icon: 'ph:lightning-duotone'
        });
    } else if (e.status === 'completed') {
        isGeneratingAi.value = false;
        aiJobProgress.value = 100;
        
        const rawElements = e.result?.elements || [];
        if (rawElements.length > 0) {
            const newElements = rawElements.map((el: any) => VelnoxAIElement.newFromObject(transformAiElement(el)));
            store.setElements([...store.elements, ...newElements]);
            
            toast.add({ title: 'AI generation completed successfully.', color: 'success', icon: 'ph:check-circle' });
            resetAiJob();
        } else {
            toast.add({ title: 'No elements generated', description: 'The AI did not return elements. Try another prompt.', color: 'warning' });
            resetAiJob();
        }
    } else if (e.status === 'failed') {
        isGeneratingAi.value = false;
        aiJobError.value = e.error || 'Failed to process AI generation job.';
        toast.add({ title: 'AI generation failed.', description: aiJobError.value || undefined, color: 'error', icon: 'ph:warning-circle' });
    }
}

onMounted(() => {
    const user = (usePage().props as any).auth?.user;
    if ((window as any).Echo) {
        if (user?.id) {
            (window as any).Echo.private(`user.${user.id}`)
                .listen('.AiJobStatusUpdated', (e: any) => {
                    handleAiJobUpdate(e);
                });
        }
    }
});

onUnmounted(() => {
    const user = (usePage().props as any).auth?.user;
    if ((window as any).Echo && user?.id) {
        (window as any).Echo.leave(`user.${user.id}`);
    }
    if ((window as any).Echo && aiJobId.value) {
        (window as any).Echo.leave(`ai-job.${aiJobId.value}`);
    }
});

async function generateAiSection() {
    if (!aiPrompt.value || isGeneratingAi.value) return;
    
    isGeneratingAi.value = true;
    aiJobStatus.value = 'queued';
    aiJobError.value = null;

    try {
        // Set aiJobId to a dummy value so the skeleton loader shows immediately
        const currentJobId = 'sync-job-' + Date.now();
        aiJobId.value = currentJobId;
        showAiModal.value = false;

        toast.add({ title: 'AI generation has been queued.', color: 'info', icon: 'ph:clock' });

        const response = await axios.post('/api/builder/ai/generate-section', {
            prompt: aiPrompt.value,
            async: false
        });

        // Skip if user cancelled during the request
        if (aiJobId.value !== currentJobId) return;

        if (response.data?.elements) {
            aiJobStatus.value = 'processing';
            aiJobProgress.value = 100;
            
            const rawElements = response.data.elements || [];
            if (rawElements.length > 0) {
                const newElements = rawElements.map((el: any) => VelnoxAIElement.newFromObject(transformAiElement(el)));
                store.setElements([...store.elements, ...newElements]);
                
                toast.add({ title: 'AI generation completed successfully.', color: 'success', icon: 'ph:check-circle' });
            } else {
                toast.add({ title: 'No elements generated', description: 'The AI did not return elements. Try another prompt.', color: 'warning' });
            }
            isGeneratingAi.value = false;
            aiPrompt.value = '';
            resetAiJob();
        }
    } catch (e: any) {
        // Skip if user cancelled during the request
        if (aiJobId.value === null) return;
        
        isGeneratingAi.value = false;
        aiJobStatus.value = 'failed';
        aiJobError.value = e.response?.data?.error || e.response?.data?.message || e.message;
        toast.add({ title: 'AI generation failed.', description: aiJobError.value || undefined, color: 'error' });
    }
}

async function retryAiJob() {
    generateAiSection();
}

async function cancelAiJob() {
    isGeneratingAi.value = false;
    resetAiJob();
    toast.add({ title: 'AI Generation Cancelled', color: 'neutral', icon: 'ph:x-circle' });
}

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
] satisfies TabsItem[];

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

    const currentUser = usePage<any>().props.auth?.user;

    if (window.Echo && editable.id && currentUser) {
        const channelName = type === 'layout' ? `layout.${editable.id}` : `page.${editable.id}`;
        
        window.Echo.join(channelName)
            .here((users: any[]) => {
                // Initial users
            })
            .joining((user: any) => {
                toast.add({ title: `${user.name} joined the editing session`, color: 'info' });
            })
            .leaving((user: any) => {
                toast.add({ title: `${user.name} left the editing session`, color: 'info' });
            })
            .listen('.Modules\\Page\\Events\\PageContentUpdated', (e: any) => {
                if (e.userId !== currentUser.id) {
                    const parsedContent = e.content.map((item: any) => VelnoxAIElement.fromObject(item));
                    store.setElements(parsedContent);
                    toast.add({ title: 'Page content was updated by another user.', color: 'primary' });
                }
            })
            .listen('.Modules\\Layout\\Events\\LayoutContentUpdated', (e: any) => {
                if (e.userId !== currentUser.id) {
                    const parsedContent = e.content.map((item: any) => VelnoxAIElement.fromObject(item));
                    store.setElements(parsedContent);
                    toast.add({ title: 'Layout content was updated by another user.', color: 'primary' });
                }
            });
    }
});

onBeforeMount(() => {
    const pageContent = (editable.content || []).map((item: TElement) =>
        VelnoxAIElement.fromObject(item),
    );
    if (!!layout) {
        const layoutContent = (layout.content || []).map((item: TElement) =>
            VelnoxAIElement.fromObject(item),
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
                const item: VelnoxAIElement = sourceData.item as VelnoxAIElement;

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
