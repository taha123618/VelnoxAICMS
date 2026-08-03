<template>
    <AdminLayout :breadcrumbs="breadcrumbs">
        <Head title="Workflow Automations" />

        <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            <!-- Header Section -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Workflow Automations</h1>
                    <p class="mt-1 text-sm text-neutral-400">
                        Design visual automation flows, triggers, conditions, and action execution sequences.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex gap-3">
                    <UButton
                        color="primary"
                        variant="soft"
                        size="xs"
                        icon="ph:sparkle"
                        @click.prevent="() => { showAiModal = true; }"
                    >
                        Generate Workflow with AI
                    </UButton>
                    <UButton
                        color="primary"
                        icon="ph:plus-circle"   
                        size="xs"
                        @click.prevent="() => { showCreateModal = true; }"
                    >
                        New Workflow
                    </UButton>
                </div>
            </div>

            <!-- Workflow Visual Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <UCard
                    v-for="workflow in workflowList"
                    :key="workflow.id"
                    class="flex flex-col border border-neutral-800 bg-neutral-900/60 hover:border-neutral-700 transition-all shadow-xl"
                >
                    <template #header>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                    <UIcon name="ph:git-fork" class="w-5 h-5" />
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-white">{{ workflow.name }}</h3>
                                    <span class="text-xs text-neutral-400">Trigger: {{ workflow.trigger.type }}</span>
                                </div>
                            </div>
                            <UBadge
                                :color="workflow.is_active ? 'success' : 'neutral'"
                                variant="subtle"
                                size="sm"
                            >
                                {{ workflow.is_active ? 'Active' : 'Draft' }}
                            </UBadge>
                        </div>
                    </template>

                    <div class="space-y-4 py-2 flex-1">
                        <p class="text-sm text-neutral-400 line-clamp-2">
                            {{ workflow.description }}
                        </p>

                        <!-- Step count badge -->
                        <div class="flex items-center gap-4 text-xs text-neutral-400">
                            <span class="flex items-center gap-1.5">
                                <UIcon name="ph:tree-structure" class="w-4 h-4 text-indigo-400" />
                                {{ workflow.nodes ? workflow.nodes.length : 0 }} Steps
                            </span>
                            <span class="flex items-center gap-1.5">
                                <UIcon name="ph:clock-afternoon" class="w-4 h-4 text-amber-400" />
                                Created {{ workflow.created_at || 'Recently' }}
                            </span>
                        </div>
                    </div>

                    <template #footer>
                        <div class="flex justify-end gap-2">
                            <UButton
                                size="xs"
                                color="neutral"
                                variant="ghost"
                                icon="ph:eye"
                                @click="previewWorkflow(workflow)"
                            >
                                Inspect Flow
                            </UButton>
                            <UButton
                                size="xs"
                                color="primary"
                                variant="soft"
                                icon="ph:pencil-simple"
                                @click="editWorkflow(workflow)"
                            >
                                Edit Flow
                            </UButton>
                        </div>
                    </template>
                </UCard>
            </div>

            <!-- Empty State -->
            <div v-if="workflowList.length === 0" class="flex flex-col items-center justify-center py-24 text-center">
                <div class="p-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 mb-4">
                    <UIcon name="ph:git-fork" class="w-10 h-10" />
                </div>
                <h3 class="text-lg font-semibold text-white mb-1">No Workflows Yet</h3>
                <p class="text-sm text-neutral-400 max-w-xs mb-6">Create your first automation workflow or generate one with AI.</p>
                <div class="flex gap-3">
                    <UButton color="primary" variant="soft" icon="ph:sparkle" size="sm" @click.prevent="() => { showAiModal = true; }">Generate with AI</UButton>
                    <UButton color="primary" icon="ph:plus-circle" size="sm" @click.prevent="() => { showCreateModal = true; }">New Workflow</UButton>
                </div>
            </div>

            <!-- Inspect Modal -->
            <UModal v-model:open="showInspectModal">
                <template #content>
                    <div class="p-6 space-y-4 font-sans text-neutral-100 max-h-[80vh] overflow-y-auto" v-if="selectedWorkflow">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <UIcon name="ph:tree-structure" class="text-indigo-400" />
                            {{ selectedWorkflow.name }} Execution Tree
                        </h3>
                        <p class="text-xs text-neutral-400">{{ selectedWorkflow.description }}</p>

                        <div class="p-4 rounded-xl bg-neutral-950 border border-neutral-800 space-y-3">
                            <div class="text-xs font-semibold text-indigo-400 uppercase tracking-wider">Trigger</div>
                            <div class="text-sm font-mono text-neutral-200">
                                {{ selectedWorkflow.trigger.type }}
                            </div>

                            <div class="text-xs font-semibold text-indigo-400 uppercase tracking-wider pt-2">Execution Nodes</div>
                            <div class="space-y-2 max-h-[50vh] overflow-y-auto pr-1">
                                <div
                                    v-for="(node, idx) in selectedWorkflow.nodes"
                                    :key="node.id || idx"
                                    class="p-2.5 rounded-lg bg-neutral-900 border border-neutral-800 text-xs flex items-center justify-between"
                                >
                                    <span class="font-medium text-neutral-200">{{ Number(idx) + 1 }}. {{ node.label }}</span>
                                    <UBadge size="xs" variant="subtle" color="primary">{{ node.type }}</UBadge>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <UButton color="neutral" variant="soft" @click.prevent="() => { showInspectModal = false; }">Close</UButton>
                        </div>
                    </div>
                </template>
            </UModal>

            <!-- Create Workflow Modal -->
            <UModal v-model:open="showCreateModal" title="Create New Workflow" description="Give your workflow a name, trigger, and steps to get started.">
                <template #content>
                    <UCard class="border border-neutral-800 shadow-2xl bg-neutral-900/95 max-h-[85vh] overflow-y-auto">
                        <template #header>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                        <UIcon name="ph:plus-circle" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h3 class="text-base font-semibold text-white">New Workflow</h3>
                                        <p class="text-xs text-neutral-400">Define your automation workflow details</p>
                                    </div>
                                </div>
                                <UBadge
                                    :color="newWorkflow.is_active ? 'success' : 'neutral'"
                                    variant="subtle"
                                    size="sm"
                                >
                                    {{ newWorkflow.is_active ? 'Active' : 'Draft' }}
                                </UBadge>
                            </div>
                        </template>

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-neutral-300">Workflow Name</label>
                                <UInput v-model="newWorkflow.name" placeholder="e.g. User Onboarding Flow" color="primary" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-neutral-300">Description</label>
                                <UTextarea v-model="newWorkflow.description" placeholder="Describe what this workflow does..." color="primary" autoresize />
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-neutral-300">Trigger Event</label>
                                <UInput v-model="newWorkflow.trigger" placeholder="e.g. user_registered, form_submitted" color="primary" />
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-neutral-800/50 border border-neutral-700/50">
                                <div>
                                    <span class="text-xs font-semibold text-white">Active Status</span>
                                    <p class="text-xs text-neutral-400">Enable workflow immediately upon creation</p>
                                </div>
                                <USwitch v-model="newWorkflow.is_active" color="primary" />
                            </div>

                            <!-- Execution Flow Steps -->
                            <div class="space-y-2 pt-2 border-t border-neutral-800">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-semibold text-indigo-400 uppercase tracking-wider">Execution Flow Steps (Nodes)</label>
                                    <UButton size="xs" color="primary" variant="soft" icon="ph:plus" @click="addNodeToNew">Add Step</UButton>
                                </div>
                                <div class="space-y-2 max-h-[30vh] overflow-y-auto pr-1">
                                    <div
                                        v-for="(node, idx) in newWorkflow.nodes"
                                        :key="idx"
                                        class="flex items-center gap-2 p-2 rounded-lg bg-neutral-900 border border-neutral-800"
                                    >
                                        <UInput v-model="node.label" placeholder="Step description e.g. Send Email" class="flex-1" size="xs" />
                                        <USelect v-model="node.type" :items="['action', 'condition', 'delay']" size="xs" class="w-28" />
                                        <UButton size="xs" color="error" variant="ghost" icon="ph:trash" @click="removeNodeFromNew(idx)" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <template #footer>
                            <div class="flex justify-end gap-2">
                                <UButton color="neutral" variant="ghost" @click.prevent="() => { showCreateModal = false; }">Cancel</UButton>
                                <UButton color="primary" :disabled="!newWorkflow.name.trim()" @click="createWorkflow">Create Workflow</UButton>
                            </div>
                        </template>
                    </UCard>
                </template>
            </UModal>

            <!-- Edit Workflow Modal -->
            <UModal v-model:open="showEditModal" title="Edit Workflow" description="Update workflow details, trigger events, steps, and status.">
                <template #content>
                    <UCard v-if="editingWorkflow" class="border border-neutral-800 shadow-2xl bg-neutral-900/95 max-h-[85vh] overflow-y-auto">
                        <template #header>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                        <UIcon name="ph:pencil-simple" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h3 class="text-base font-semibold text-white">Edit {{ editingWorkflow.name }}</h3>
                                        <p class="text-xs text-neutral-400">Modify visual automation properties</p>
                                    </div>
                                </div>
                                <UBadge
                                    :color="editingWorkflow.is_active ? 'success' : 'neutral'"
                                    variant="subtle"
                                    size="sm"
                                >
                                    {{ editingWorkflow.is_active ? 'Active' : 'Draft' }}
                                </UBadge>
                            </div>
                        </template>

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-neutral-300">Workflow Name</label>
                                <UInput v-model="editingWorkflow.name" color="primary" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-neutral-300">Description</label>
                                <UTextarea v-model="editingWorkflow.description" color="primary" autoresize />
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-neutral-300">Trigger Event</label>
                                <UInput v-model="editingWorkflow.trigger_type" color="primary" />
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-neutral-800/50 border border-neutral-700/50">
                                <div>
                                    <span class="text-xs font-semibold text-white">Active Status</span>
                                    <p class="text-xs text-neutral-400">Enable or disable automatic execution</p>
                                </div>
                                <USwitch v-model="editingWorkflow.is_active" color="primary" />
                            </div>

                            <!-- Execution Flow Steps Editor -->
                            <div class="space-y-2 pt-2 border-t border-neutral-800">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-semibold text-indigo-400 uppercase tracking-wider">Execution Flow Steps (Nodes)</label>
                                    <UButton size="xs" color="primary" variant="soft" icon="ph:plus" @click="addNodeToEdit">Add Step</UButton>
                                </div>
                                <div class="space-y-2 max-h-[30vh] overflow-y-auto pr-1">
                                    <div
                                        v-for="(node, idx) in editingWorkflow.nodes"
                                        :key="idx"
                                        class="flex items-center gap-2 p-2 rounded-lg bg-neutral-900 border border-neutral-800"
                                    >
                                        <UInput v-model="node.label" placeholder="Step description e.g. Send Email" class="flex-1" size="xs" />
                                        <USelect v-model="node.type" :items="['action', 'condition', 'delay']" size="xs" class="w-28" />
                                        <UButton size="xs" color="error" variant="ghost" icon="ph:trash" @click="removeNodeFromEdit(idx)" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <template #footer>
                            <div class="flex justify-between items-center w-full">
                                <UButton color="error" variant="ghost" icon="ph:trash" @click="deleteWorkflow(editingWorkflow)">Delete</UButton>
                                <div class="flex gap-2">
                                    <UButton color="neutral" variant="ghost" @click.prevent="() => { showEditModal = false; }">Cancel</UButton>
                                    <UButton color="primary" icon="ph:check" @click="saveWorkflowEdit">Save Changes</UButton>
                                </div>
                            </div>
                        </template>
                    </UCard>
                </template>
            </UModal>

            <!-- AI Prompt Modal Component -->
            <AiPromptModal
                v-model:isOpen="showAiModal"
                title="AI Workflow Architect"
                description="Describe your automation goal and let AI build the triggers, steps, and conditions."
                endpoint="/api/workflow/generate-workflow"
                placeholder="Create a user onboarding workflow with email sequence, delay, and tag update..."
                :suggestions="['Onboarding Email Sequence Workflow', 'Abandoned Cart Recovery Workflow', 'Lead Scoring & Assignment Flow']"
                @success="handleAiSuccess"
            />
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import AiPromptModal from '@/components/AiPromptModal.vue';
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js';

const props = defineProps<{
    workflows?: any[];
}>();

const breadcrumbs = [
    { label: 'Workflows', icon: 'ph:git-fork' },
];

const toast = useToast();
const showAiModal = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showInspectModal = ref(false);
const selectedWorkflow = ref<any>(null);
const editingWorkflow = ref<any>(null);

function editWorkflow(workflow: any) {
    editingWorkflow.value = {
        ...workflow,
        trigger_type: workflow.trigger?.type || 'custom_event',
        nodes: workflow.nodes ? JSON.parse(JSON.stringify(workflow.nodes)) : [],
    };
    showEditModal.value = true;
}

function addNodeToEdit() {
    if (!editingWorkflow.value.nodes) {
        editingWorkflow.value.nodes = [];
    }
    editingWorkflow.value.nodes.push({ label: 'New Action Step', type: 'action' });
}

function removeNodeFromEdit(index: number | string) {
    editingWorkflow.value.nodes.splice(Number(index), 1);
}

function saveWorkflowEdit() {
    if (!editingWorkflow.value) return;

    router.put(`/admin/workflows/${editingWorkflow.value.id}`, {
        name: editingWorkflow.value.name,
        description: editingWorkflow.value.description,
        trigger_type: editingWorkflow.value.trigger_type,
        is_active: editingWorkflow.value.is_active,
        nodes: editingWorkflow.value.nodes,
    }, {
        onSuccess: () => {
            toast.add({
                title: 'Workflow Updated',
                description: `"${editingWorkflow.value.name}" has been updated.`,
                color: 'success',
            });
            showEditModal.value = false;
        },
    });
}

function deleteWorkflow(workflow: any) {
    if (confirm(`Are you sure you want to delete "${workflow.name}"?`)) {
        router.delete(`/admin/workflows/${workflow.id}`, {
            onSuccess: () => {
                toast.add({
                    title: 'Workflow Deleted',
                    description: `"${workflow.name}" has been removed.`,
                    color: 'success',
                });
                showEditModal.value = false;
            },
        });
    }
}

const newWorkflow = ref({
    name: '',
    description: '',
    trigger: '',
    is_active: true,
    nodes: [
        { label: 'Initial Step Action', type: 'action' },
    ],
});

function addNodeToNew() {
    newWorkflow.value.nodes.push({ label: 'New Action Step', type: 'action' });
}

function removeNodeFromNew(index: number | string) {
    newWorkflow.value.nodes.splice(Number(index), 1);
}

function createWorkflow() {
    if (!newWorkflow.value.name.trim()) { return; }

    router.post('/admin/workflows', {
        name: newWorkflow.value.name,
        description: newWorkflow.value.description,
        trigger: newWorkflow.value.trigger,
        is_active: newWorkflow.value.is_active,
        nodes: newWorkflow.value.nodes,
    }, {
        onSuccess: () => {
            toast.add({
                title: 'Workflow Created',
                description: `"${newWorkflow.value.name}" has been added.`,
                color: 'success',
            });
            newWorkflow.value = {
                name: '',
                description: '',
                trigger: '',
                is_active: true,
                nodes: [{ label: 'Initial Step Action', type: 'action' }],
            };
            showCreateModal.value = false;
        },
    });
}

const workflowList = computed(() => props.workflows || []);

function handleAiSuccess(result: any) {
    const generated = result.workflow || result;
    if (generated) {
        toast.add({
            title: 'Workflow Generated!',
            description: `Successfully created workflow: "${generated.name || 'AI Flow'}"`,
            color: 'success',
        });
        router.reload();
    }
}

function previewWorkflow(workflow: any) {
    selectedWorkflow.value = workflow;
    showInspectModal.value = true;
}
</script>

<style scoped></style>