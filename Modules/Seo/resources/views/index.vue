<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="SEO Optimization & Schema" />

        <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            <!-- Header Section -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">SEO & OpenGraph Metadata</h1>
                    <p class="mt-1 text-sm text-neutral-400">
                        Optimize page titles, meta descriptions, OpenGraph tags, and Schema.org structured data.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex gap-3">
                    <UButton
                        color="primary"
                        variant="ghost"
                        icon="ph:sparkle"
                        @click.prevent="() => { showAiModal = true; }"
                    >
                        Optimize SEO with AI
                    </UButton>
                </div>
            </div>

            <!-- SEO Health Cards Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Overall SEO Score</span>
                        <UIcon name="ph:chart-line-up" class="text-emerald-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">94 / 100</div>
                    <div class="text-[11px] text-emerald-400">Good Search Coverage</div>
                </div>

                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Indexed Pages</span>
                        <UIcon name="ph:globe-hemisphere-west" class="text-indigo-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">28 Pages</div>
                    <div class="text-[11px] text-neutral-400">Sitemap XML Active</div>
                </div>

                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>OpenGraph Status</span>
                        <UIcon name="ph:share-network" class="text-purple-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">100% Ready</div>
                    <div class="text-[11px] text-purple-400">Social Cards Enabled</div>
                </div>

                <div class="p-5 rounded-2xl bg-neutral-900/80 border border-neutral-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span>Schema JSON-LD</span>
                        <UIcon name="ph:code" class="text-amber-400 w-5 h-5" />
                    </div>
                    <div class="text-2xl font-bold text-white">Structured</div>
                    <div class="text-[11px] text-amber-400">Rich Snippets Enabled</div>
                </div>
            </div>

            <!-- Page SEO Table -->
            <UCard class="border border-neutral-800 bg-neutral-900/60 shadow-xl">
                <template #header>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-white">Managed Pages SEO Overview</h3>
                        <UBadge color="primary" variant="subtle" size="sm">4 Pages Tracked</UBadge>
                    </div>
                </template>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-neutral-300 divide-y divide-neutral-800">
                        <thead class="bg-neutral-950/60 text-xs font-semibold text-neutral-400 uppercase">
                            <tr>
                                <th class="py-3.5 px-4">Page Title</th>
                                <th class="py-3.5 px-4">Meta Description</th>
                                <th class="py-3.5 px-4">Keywords</th>
                                <th class="py-3.5 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-800/60 font-mono text-xs">
                            <tr v-for="item in pageSeoItems" :key="item.id" class="hover:bg-neutral-800/40 transition-colors">
                                <td class="py-3.5 px-4 font-sans font-medium text-white">{{ item.title }}</td>
                                <td class="py-3.5 px-4 font-sans text-neutral-400 max-w-xs truncate">{{ item.description }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        <UBadge v-for="kw in item.keywords" :key="kw" size="xs" variant="outline" color="primary">{{ kw }}</UBadge>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <UButton
                                        size="xs"
                                        color="primary"
                                        variant="ghost"
                                        icon="ph:sparkle"
                                        @click="optimizePageSeo(item)"
                                    >
                                        AI Optimize
                                    </UButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </UCard>

            <!-- AI Prompt Modal Component -->
            <AiPromptModal
                v-model:isOpen="showAiModal"
                title="AI Technical SEO Optimizer"
                description="Generate meta title, description, OpenGraph card data, keywords, and JSON-LD schema."
                endpoint="/api/seo/generate-metadata"
                placeholder="Generate SEO metadata for a SaaS AI Website Builder homepage..."
                :suggestions="['SaaS Landing Page SEO', 'E-commerce Product Page SEO', 'Engineering Blog Post SEO']"
                @success="handleAiSuccess"
            />
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import AiPromptModal from '@/components/AiPromptModal.vue';
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js';

const breadcrumbs = [
    { label: 'SEO Management', href: route('seo.index') },
];

const toast = useToast();
const showAiModal = ref(false);
const selectedItem = ref<any>(null);

const pageSeoItems = ref<any[]>([
    {
        id: 1,
        title: 'Home - ZioraCMS AI Drag & Drop Builder',
        description: 'Build stunning high-conversion web applications with AI and Vue.',
        keywords: ['cms', 'vue', 'laravel', 'ai builder'],
    },
    {
        id: 2,
        title: 'Marketplace Plugins & Extension Hub',
        description: 'Browse themes, plugins, and workflow extensions for ZioraCMS.',
        keywords: ['marketplace', 'plugins', 'themes'],
    },
    {
        id: 3,
        title: 'Automation & Visual Workflows',
        description: 'Automate marketing campaigns, emails, and webhook triggers easily.',
        keywords: ['automation', 'workflows', 'webhooks'],
    },
]);

function optimizePageSeo(item: any) {
    selectedItem.value = item;
    showAiModal.value = true;
}

function handleAiSuccess(result: any) {
    if (result.meta) {
        const meta = result.meta;
        if (selectedItem.value) {
            selectedItem.value.title = meta.meta_title || selectedItem.value.title;
            selectedItem.value.description = meta.meta_description || selectedItem.value.description;
            if (meta.keywords && Array.isArray(meta.keywords)) {
                selectedItem.value.keywords = meta.keywords;
            }
        }
        toast.add({
            title: 'SEO Optimized!',
            description: `Generated SEO metadata: "${meta.meta_title || 'Optimized Page'}"`,
            color: 'success',
        });
    }
}
</script>

<style scoped></style>