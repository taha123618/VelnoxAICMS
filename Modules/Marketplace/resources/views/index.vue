<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Marketplace &amp; Extensions</h1>
            <div class="flex gap-2">
                <UButton icon="ph:sparkle" color="primary" variant="ghost" @click.prevent="() => { showAiModal = true; }">Generate Listing</UButton>
                <UButton icon="ph:upload-simple" color="primary" @click.prevent="() => { showUploadModal = true; }">Upload Plugin/Theme</UButton>
            </div>
        </div>

        <UTabs :items="tabs" class="w-full">
            <template #plugins>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <UCard v-for="module in pluginsList" :key="module.alias" class="flex flex-col">
                        <template #header>
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ module.name }}</h3>
                                <div class="flex items-center gap-2">
                                    <UBadge v-if="module.path === ''" color="info" variant="subtle" icon="ph:sparkle">
                                        AI Generated
                                    </UBadge>
                                    <UBadge v-else :color="module.is_enabled ? 'success' : 'neutral'" variant="subtle">
                                        {{ module.is_enabled ? 'Enabled' : 'Disabled' }}
                                    </UBadge>
                                </div>
                            </div>
                        </template>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 line-clamp-3">
                                {{ module.description || 'No description provided.' }}
                            </p>
                            <!-- AI spec preview: features + tags -->
                            <div v-if="module.path === '' && module.ai_spec">
                                <ul v-if="module.ai_spec.features?.length" class="mt-3 space-y-1">
                                    <li
                                        v-for="feat in module.ai_spec.features.slice(0, 3)"
                                        :key="feat"
                                        class="flex items-start gap-1.5 text-xs text-neutral-500 dark:text-neutral-400"
                                    >
                                        <span class="i-ph-check-circle text-success-500 mt-0.5 shrink-0" />
                                        {{ feat }}
                                    </li>
                                    <li v-if="module.ai_spec.features.length > 3" class="text-xs text-neutral-400 italic">
                                        + {{ module.ai_spec.features.length - 3 }} more features…
                                    </li>
                                </ul>
                                <div v-if="module.ai_spec.suggested_price" class="mt-3 text-xs font-medium text-success-600 dark:text-success-400">
                                    Suggested Price: ${{ module.ai_spec.suggested_price }}
                                </div>
                                <div v-if="module.ai_spec.tags?.length" class="mt-2 flex flex-wrap gap-1">
                                    <UBadge
                                        v-for="tag in module.ai_spec.tags"
                                        :key="tag"
                                        color="neutral"
                                        variant="subtle"
                                        size="xs"
                                    >{{ tag }}</UBadge>
                                </div>
                            </div>
                            <div class="mt-4 text-xs text-neutral-400">
                                Version: {{ module.version }}
                            </div>
                        </div>
                        <template #footer>
                            <div class="mt-4 space-y-2">
                                <!-- AI-generated: show spec download + view full spec buttons -->
                                <template v-if="module.path === ''">
                                    <div class="flex gap-2">
                                        <UButton
                                            icon="ph:download-simple"
                                            color="primary"
                                            variant="solid"
                                            size="sm"
                                            class="flex-1"
                                            @click="downloadSpec(module)"
                                        >
                                            Download Spec (JSON)
                                        </UButton>
                                        <UButton
                                            icon="ph:eye"
                                            color="neutral"
                                            variant="ghost"
                                            size="sm"
                                            @click="openSpecModal(module)"
                                        />
                                        <UButton
                                            icon="ph:trash"
                                            color="error"
                                            variant="ghost"
                                            size="sm"
                                            :loading="isDeleting === (module.job_id || module.alias)"
                                            :disabled="isDeleting !== null || isToggling !== null"
                                            @click="deleteItem(module)"
                                        />
                                    </div>
                                    <p class="text-xs text-neutral-400 text-center">
                                        Build from this spec, then
                                        <button class="underline hover:text-primary-500" @click="() => { showUploadModal = true; }">upload the ZIP</button>
                                        to activate.
                                    </p>
                                </template>
                                <div v-else class="flex flex-col gap-2 w-full">
                                    <form @submit.prevent="toggleModule(module.name)" class="w-full">
                                        <UButton
                                            :color="module.is_enabled ? 'error' : 'primary'"
                                            :variant="module.is_enabled ? 'soft' : 'solid'"
                                            block
                                            type="submit"
                                            :disabled="isToggling === module.name || isDeleting !== null"
                                            :loading="isToggling === module.name"
                                        >
                                            {{ module.is_enabled ? 'Disable Plugin' : 'Enable Plugin' }}
                                        </UButton>
                                    </form>
                                    <UButton
                                        v-if="!isCoreModule(module.name)"
                                        icon="ph:trash"
                                        color="error"
                                        variant="soft"
                                        block
                                        :loading="isDeleting === module.name"
                                        :disabled="isToggling !== null || isDeleting !== null"
                                        @click="deleteItem(module)"
                                    >
                                        Delete Plugin
                                    </UButton>
                                </div>
                            </div>
                        </template>
                    </UCard>
                    <div v-if="!pluginsList.length" class="col-span-full py-8 text-center text-neutral-500">
                        No plugins installed yet.
                    </div>
                </div>
            </template>
            <template #themes>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <UCard v-for="theme in themesList" :key="theme.alias" class="flex flex-col" :class="{'ring-2 ring-primary-500': theme.is_enabled}">
                        <template #header>
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ theme.name }}</h3>
                                <div class="flex items-center gap-2">
                                    <UBadge v-if="theme.path === ''" color="info" variant="subtle" icon="ph:sparkle">
                                        AI Generated
                                    </UBadge>
                                    <UBadge v-else-if="theme.is_enabled" color="primary" variant="solid">Active Theme</UBadge>
                                </div>
                            </div>
                        </template>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 line-clamp-3">
                                {{ theme.description || 'No description provided.' }}
                            </p>
                            <!-- AI spec preview: features + tags -->
                            <div v-if="theme.path === '' && theme.ai_spec">
                                <ul v-if="theme.ai_spec.features?.length" class="mt-3 space-y-1">
                                    <li
                                        v-for="feat in theme.ai_spec.features.slice(0, 3)"
                                        :key="feat"
                                        class="flex items-start gap-1.5 text-xs text-neutral-500 dark:text-neutral-400"
                                    >
                                        <span class="i-ph-check-circle text-success-500 mt-0.5 shrink-0" />
                                        {{ feat }}
                                    </li>
                                    <li v-if="theme.ai_spec.features.length > 3" class="text-xs text-neutral-400 italic">
                                        + {{ theme.ai_spec.features.length - 3 }} more features…
                                    </li>
                                </ul>
                                <div v-if="theme.ai_spec.suggested_price" class="mt-3 text-xs font-medium text-success-600 dark:text-success-400">
                                    Suggested Price: ${{ theme.ai_spec.suggested_price }}
                                </div>
                                <div v-if="theme.ai_spec.tags?.length" class="mt-2 flex flex-wrap gap-1">
                                    <UBadge
                                        v-for="tag in theme.ai_spec.tags"
                                        :key="tag"
                                        color="neutral"
                                        variant="subtle"
                                        size="xs"
                                    >{{ tag }}</UBadge>
                                </div>
                            </div>
                            <div class="mt-4 text-xs text-neutral-400">
                                Version: {{ theme.version }}
                            </div>
                        </div>
                        <template #footer>
                            <div class="mt-4 space-y-2">
                                <template v-if="theme.path === ''">
                                    <div class="flex gap-2">
                                        <UButton
                                            icon="ph:download-simple"
                                            color="primary"
                                            variant="solid"
                                            size="sm"
                                            class="flex-1"
                                            @click="downloadSpec(theme)"
                                        >
                                            Download Spec (JSON)
                                        </UButton>
                                        <UButton
                                            icon="ph:eye"
                                            color="neutral"
                                            variant="ghost"
                                            size="sm"
                                            @click="openSpecModal(theme)"
                                        />
                                        <UButton
                                            icon="ph:trash"
                                            color="error"
                                            variant="ghost"
                                            size="sm"
                                            :loading="isDeleting === (theme.job_id || theme.alias)"
                                            :disabled="isDeleting !== null || isToggling !== null"
                                            @click="deleteItem(theme)"
                                        />
                                    </div>
                                    <p class="text-xs text-neutral-400 text-center">
                                        Build from this spec, then
                                        <button class="underline hover:text-primary-500" @click="() => { showUploadModal = true; }">upload the ZIP</button>
                                        to activate.
                                    </p>
                                </template>
                                <div v-else class="flex flex-col gap-2 w-full">
                                    <form @submit.prevent="toggleModule(theme.name)" class="w-full">
                                        <UButton
                                            :color="theme.is_enabled ? 'neutral' : 'primary'"
                                            :variant="theme.is_enabled ? 'ghost' : 'solid'"
                                            block
                                            type="submit"
                                            :disabled="theme.is_enabled || isToggling === theme.name || isDeleting !== null"
                                            :loading="isToggling === theme.name"
                                        >
                                            {{ theme.is_enabled ? 'Currently Active' : 'Activate Theme' }}
                                        </UButton>
                                    </form>
                                    <UButton
                                        v-if="!isCoreModule(theme.name)"
                                        icon="ph:trash"
                                        color="error"
                                        variant="soft"
                                        block
                                        :loading="isDeleting === theme.name"
                                        :disabled="isToggling !== null || isDeleting !== null"
                                        @click="deleteItem(theme)"
                                    >
                                        Delete Theme
                                    </UButton>
                                </div>
                            </div>
                        </template>
                    </UCard>
                    <div v-if="!themesList.length" class="col-span-full py-8 text-center text-neutral-500">
                        No themes installed yet.
                    </div>
                </div>
            </template>
        </UTabs>

        <!-- Upload Modal -->
        <UModal v-model="showUploadModal">
            <UCard>
                <template #header>
                    <h3 class="text-lg font-semibold">Upload Extension</h3>
                </template>
                <form @submit.prevent="uploadPlugin">
                    <div class="space-y-4">
                        <p class="text-sm text-neutral-400">Upload a ZioraCMS compatible plugin or theme (.zip file).</p>
                        <UInput type="file" accept=".zip" @change="handleFileChange" required />
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <UButton color="neutral" variant="ghost" @click.prevent="() => { showUploadModal = false; }">Cancel</UButton>
                        <UButton type="submit" color="primary" :loading="isUploading" :disabled="!selectedFile">Install</UButton>
                    </div>
                </form>
            </UCard>
        </UModal>

        <!-- AI Spec Viewer Modal -->
        <UModal v-model="showSpecModal">
            <UCard v-if="selectedSpec" class="sm:max-w-2xl">
                <template #header>
                    <div class="flex items-center gap-2">
                        <span class="i-ph-sparkle text-primary-500 text-lg" />
                        <h3 class="text-lg font-semibold">{{ selectedSpec.name }} — Full Spec</h3>
                    </div>
                </template>
                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                    <div v-if="selectedSpec.ai_spec?.full_description">
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1">Full Description</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ selectedSpec.ai_spec.full_description }}</p>
                    </div>
                    <div v-if="selectedSpec.ai_spec?.features?.length">
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1">Features</p>
                        <ul class="space-y-1">
                            <li v-for="feat in selectedSpec.ai_spec.features" :key="feat" class="flex items-start gap-2 text-sm text-neutral-600 dark:text-neutral-300">
                                <span class="i-ph-check-circle text-success-500 mt-0.5 shrink-0" />
                                {{ feat }}
                            </li>
                        </ul>
                    </div>
                    <div v-if="selectedSpec.ai_spec?.tags?.length">
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-1">Tags</p>
                        <div class="flex flex-wrap gap-1">
                            <UBadge v-for="tag in selectedSpec.ai_spec.tags" :key="tag" color="neutral" variant="subtle">{{ tag }}</UBadge>
                        </div>
                    </div>
                    <div v-if="selectedSpec.ai_spec?.suggested_price" class="flex items-center gap-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Suggested Price</p>
                        <span class="text-sm font-bold text-success-600 dark:text-success-400">${{ selectedSpec.ai_spec.suggested_price }}</span>
                    </div>
                    <div v-if="selectedSpec.ai_spec?.category" class="flex items-center gap-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Category</p>
                        <span class="text-sm text-neutral-600 dark:text-neutral-300">{{ selectedSpec.ai_spec.category }}</span>
                    </div>
                    <div v-if="selectedSpec.ai_spec?.generated_at" class="text-xs text-neutral-400">
                        Generated: {{ new Date(selectedSpec.ai_spec.generated_at).toLocaleString() }}
                    </div>
                </div>
                <template #footer>
                    <div class="flex justify-end gap-2">
                        <UButton color="neutral" variant="ghost" @click.prevent="() => { showSpecModal = false; }">Close</UButton>
                        <UButton icon="ph:trash" color="error" variant="ghost" @click="deleteItem(selectedSpec!)">Delete Concept</UButton>
                        <UButton icon="ph:download-simple" color="primary" @click="downloadSpec(selectedSpec!)">Download JSON Spec</UButton>
                    </div>
                </template>
            </UCard>
        </UModal>

        <AiPromptModal
            v-model:isOpen="showAiModal"
            title="AI Marketplace Listing Architect"
            description="Generate plugin or theme listing details, features, tags, and suggested pricing."
            endpoint="/api/marketplace/generate-listing"
            placeholder="Generate a listing for an e-commerce payment gateway plugin..."
            :suggestions="['Dark mode SaaS dashboard theme', 'SEO Optimization &amp; Schema Plugin']"
            @success="handleAiSuccess"
        />
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js';
import AiPromptModal from '@/components/AiPromptModal.vue';

interface AiSpec {
    title: string | null;
    slug: string | null;
    category: string | null;
    short_description: string | null;
    full_description: string | null;
    features: string[];
    tags: string[];
    suggested_price: number | null;
    generated_at: string | null;
}

interface MarketplaceItem {
    name: string;
    alias: string;
    description: string;
    path: string;
    is_enabled: boolean;
    version: string;
    job_id?: string;
    ai_spec?: AiSpec;
}

const props = defineProps<{
    plugins: MarketplaceItem[];
    themes: MarketplaceItem[];
    activeTheme: string;
}>();

const breadcrumbs = [
    { label: 'Marketplace', href: route('marketplace.index') },
];

const tabs = [
    { label: 'Plugins', slot: 'plugins', icon: 'ph:puzzle-piece' },
    { label: 'Themes', slot: 'themes', icon: 'ph:paint-brush-broad' },
];

const coreModules = [
    'Acl', 'Ai', 'ApiTokens', 'AuditLog', 'Auth', 'Automation',
    'Builder', 'Category', 'Contacts', 'Content', 'Dashboard',
    'Forms', 'Layout', 'Localization', 'Marketplace', 'Media',
    'Menu', 'Page', 'Seo', 'Settings', 'Testimonial', 'Visits',
    'Workflow'
];

const toast = useToast();
const showUploadModal = ref(false);
const showAiModal = ref(false);
const showSpecModal = ref(false);
const selectedSpec = ref<MarketplaceItem | null>(null);
const isToggling = ref<string | null>(null);
const isDeleting = ref<string | null>(null);
const isUploading = ref(false);
const selectedFile = ref<File | null>(null);

const pluginsList = computed(() => props.plugins || []);
const themesList = computed(() => props.themes || []);

function isCoreModule(name: string): boolean {
    return coreModules.map(m => m.toLowerCase()).includes(name.toLowerCase());
}

function openSpecModal(item: MarketplaceItem) {
    selectedSpec.value = item;
    showSpecModal.value = true;
}

function downloadSpec(item: MarketplaceItem) {
    const spec = item.ai_spec ?? { title: item.name, description: item.description };
    const blob = new Blob([JSON.stringify(spec, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const anchor = document.createElement('a');
    anchor.href = url;
    anchor.download = `${item.alias || item.name.toLowerCase().replace(/\s+/g, '-')}-spec.json`;
    anchor.click();
    URL.revokeObjectURL(url);
    toast.add({ title: 'Spec downloaded', description: `${item.name} spec saved as JSON.`, color: 'success' });
}

function deleteItem(item: MarketplaceItem) {
    const key = item.job_id ?? item.name;
    const isAi = item.path === '';
    
    if (!confirm(`Are you sure you want to delete "${item.name}"?` + (isAi ? '' : ' This will delete all its files from disk! This action is irreversible.'))) {
        return;
    }
    
    isDeleting.value = key;
    router.delete(route('marketplace.destroy', { name: key }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ title: isAi ? 'Concept deleted' : 'Plugin deleted', color: 'success' });
            if (showSpecModal.value && selectedSpec.value?.name === item.name) {
                showSpecModal.value = false;
            }
        },
        onError: (errors) => {
            toast.add({ title: 'Deletion failed', description: Object.values(errors)[0] as string, color: 'error' });
        },
        onFinish: () => {
            isDeleting.value = null;
        }
    });
}

function handleAiSuccess(result: any) {
    const listing = result.listing || result;
    if (listing) {
        toast.add({
            title: 'AI Listing Generated',
            description: `Successfully generated "${listing.title || 'Extension'}"!`,
            color: 'success',
        });
        router.reload();
    }
}

function toggleModule(moduleName: string) {
    isToggling.value = moduleName;
    router.post(route('marketplace.toggle', { module: moduleName }), {}, {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ title: 'Status updated', color: 'success' });
        },
        onError: (errors) => {
            toast.add({ title: 'Error', description: Object.values(errors)[0] as string, color: 'error' });
        },
        onFinish: () => {
            isToggling.value = null;
        }
    });
}

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files?.length) {
        selectedFile.value = target.files[0];
    }
}

function uploadPlugin() {
    if (!selectedFile.value) { return; }

    isUploading.value = true;
    const formData = new FormData();
    formData.append('plugin', selectedFile.value);

    router.post(route('marketplace.install'), formData, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ title: 'Extension installed successfully', color: 'success' });
            showUploadModal.value = false;
            selectedFile.value = null;
        },
        onError: (errors) => {
            toast.add({ title: 'Upload failed', description: Object.values(errors)[0] as string, color: 'error' });
        },
        onFinish: () => {
            isUploading.value = false;
        }
    });
}
</script>