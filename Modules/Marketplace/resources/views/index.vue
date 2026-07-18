<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Marketplace & Extensions</h1>
            <UButton icon="ph:upload-simple" color="primary" @click="showUploadModal = true">Upload Plugin/Theme</UButton>
        </div>

        <UTabs :items="tabs" class="w-full">
            <template #plugins>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <UCard v-for="module in plugins" :key="module.alias" class="flex flex-col">
                        <template #header>
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ module.name }}</h3>
                                <UBadge :color="module.is_enabled ? 'success' : 'neutral'" variant="subtle">
                                    {{ module.is_enabled ? 'Enabled' : 'Disabled' }}
                                </UBadge>
                            </div>
                        </template>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 line-clamp-3">
                                {{ module.description || 'No description provided.' }}
                            </p>
                            <div class="mt-4 text-xs text-neutral-400">
                                Version: {{ module.version }}
                            </div>
                        </div>
                        <template #footer>
                            <div class="flex justify-end mt-4">
                                <form @submit.prevent="toggleModule(module.name)" class="w-full">
                                    <UButton 
                                        :color="module.is_enabled ? 'error' : 'primary'" 
                                        :variant="module.is_enabled ? 'soft' : 'solid'"
                                        block
                                        type="submit"
                                        :disabled="isToggling === module.name"
                                        :loading="isToggling === module.name"
                                    >
                                        {{ module.is_enabled ? 'Disable Plugin' : 'Enable Plugin' }}
                                    </UButton>
                                </form>
                            </div>
                        </template>
                    </UCard>
                    <div v-if="!plugins.length" class="col-span-full py-8 text-center text-neutral-500">
                        No plugins installed yet.
                    </div>
                </div>
            </template>
            <template #themes>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <UCard v-for="theme in themes" :key="theme.alias" class="flex flex-col" :class="{'ring-2 ring-primary-500': theme.is_enabled}">
                        <template #header>
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ theme.name }}</h3>
                                <UBadge v-if="theme.is_enabled" color="primary" variant="solid">Active Theme</UBadge>
                            </div>
                        </template>
                        <div class="flex-1">
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 line-clamp-3">
                                {{ theme.description || 'No description provided.' }}
                            </p>
                            <div class="mt-4 text-xs text-neutral-400">
                                Version: {{ theme.version }}
                            </div>
                        </div>
                        <template #footer>
                            <div class="flex justify-end mt-4">
                                <form @submit.prevent="toggleModule(theme.name)" class="w-full">
                                    <UButton 
                                        :color="theme.is_enabled ? 'neutral' : 'primary'" 
                                        :variant="theme.is_enabled ? 'ghost' : 'solid'"
                                        block
                                        type="submit"
                                        :disabled="theme.is_enabled || isToggling === theme.name"
                                        :loading="isToggling === theme.name"
                                    >
                                        {{ theme.is_enabled ? 'Currently Active' : 'Activate Theme' }}
                                    </UButton>
                                </form>
                            </div>
                        </template>
                    </UCard>
                    <div v-if="!themes.length" class="col-span-full py-8 text-center text-neutral-500">
                        No themes installed yet.
                    </div>
                </div>
            </template>
        </UTabs>

        <!-- Upload Plugin Modal -->
        <UModal v-model="showUploadModal" title="Upload Extension (.zip)">
            <UCard>
                <template #header>
                    <div class="flex items-center gap-2">
                        <UIcon name="ph:upload-simple" class="text-primary-500" />
                        <h3 class="text-base font-semibold leading-6 text-white">Upload Extension</h3>
                    </div>
                </template>
                <form @submit.prevent="uploadPlugin">
                    <div class="space-y-4">
                        <p class="text-sm text-neutral-400">Upload a ZioraCMS compatible plugin or theme (.zip file).</p>
                        <UInput type="file" accept=".zip" @change="handleFileChange" required />
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <UButton color="neutral" variant="ghost" @click="showUploadModal = false">Cancel</UButton>
                        <UButton type="submit" color="primary" :loading="isUploading" :disabled="!selectedFile">Install</UButton>
                    </div>
                </form>
            </UCard>
        </UModal>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js';

const props = defineProps<{
    plugins: any[];
    themes: any[];
    activeTheme: string;
}>();

const breadcrumbs = [
    { label: 'Marketplace', href: route('marketplace.index') },
];

const tabs = [
    { label: 'Plugins', slot: 'plugins', icon: 'ph:puzzle-piece' },
    { label: 'Themes', slot: 'themes', icon: 'ph:paint-brush-broad' },
];

const toast = useToast();
const showUploadModal = ref(false);
const isToggling = ref<string | null>(null);
const isUploading = ref(false);
const selectedFile = ref<File | null>(null);

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
    if (!selectedFile.value) return;

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