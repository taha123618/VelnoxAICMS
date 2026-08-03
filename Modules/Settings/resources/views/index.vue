<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps<{
    settings: Record<string, any[]>
}>();

const form = useForm({
    settings: [] as any[],
    files: {} as Record<string, File>
});

// Initialize form from props
const initForm = () => {
    const defaultSettings = [
        { key: 'site_name', group: 'general', type: 'string', value: 'VelnoxAI CMS' },
        { key: 'site_description', group: 'general', type: 'string', value: '' },
        { key: 'site_logo', group: 'branding', type: 'image', value: '' },
        { key: 'contact_email', group: 'general', type: 'string', value: 'hello@example.com' },
        { key: 'facebook_url', group: 'social', type: 'string', value: '' },
        { key: 'twitter_url', group: 'social', type: 'string', value: '' },
    ];

    let flatSettings: any[] = [];
    Object.values(props.settings || {}).forEach(group => {
        if (Array.isArray(group)) {
            flatSettings.push(...group);
        }
    });

    // Merge defaults with existing
    defaultSettings.forEach(def => {
        const existing = flatSettings.find(s => s.key === def.key);
        if (existing) {
            form.settings.push(existing);
        } else {
            form.settings.push(def);
        }
    });
};

initForm();

const activeTab = ref('general');

const handleFileChange = (e: Event, key: string) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.files[key] = target.files[0];
    }
};

const submit = () => {
    form.post(route('settings.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // success
        }
    });
};

</script>

<template>
    <Head title="Global Settings" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Global Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                            <li class="mr-2" role="presentation">
                                <button @click="activeTab = 'general'" :class="{'border-b-2 border-blue-600 text-blue-600': activeTab === 'general'}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="general-tab" type="button" role="tab" aria-controls="general" aria-selected="false">General</button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button @click="activeTab = 'branding'" :class="{'border-b-2 border-blue-600 text-blue-600': activeTab === 'branding'}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="branding-tab" type="button" role="tab" aria-controls="branding" aria-selected="false">Branding</button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button @click="activeTab = 'social'" :class="{'border-b-2 border-blue-600 text-blue-600': activeTab === 'social'}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="social-tab" type="button" role="tab" aria-controls="social" aria-selected="false">Social Links</button>
                            </li>
                        </ul>
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit" class="space-y-6">

                            <!-- Dynamic fields based on active tab -->
                            <div v-for="(setting, index) in form.settings" :key="setting.key">
                                <div v-if="setting.group === activeTab">
                                    <label :for="setting.key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">
                                        {{ setting.key.replace(/_/g, ' ') }}
                                    </label>
                                    
                                    <div class="mt-1">
                                        <!-- String/Text -->
                                        <input v-if="setting.type === 'string'" type="text" :id="setting.key" v-model="form.settings[index].value" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md">
                                        
                                        <!-- Boolean -->
                                        <div v-if="setting.type === 'boolean'" class="flex items-center">
                                            <input :id="setting.key" type="checkbox" v-model="form.settings[index].value" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        </div>

                                        <!-- Image -->
                                        <div v-if="setting.type === 'image'">
                                            <input type="file" @change="(e) => handleFileChange(e, setting.key)" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                            <p v-if="setting.media && setting.media.length > 0" class="mt-2 text-sm text-gray-500">Current file exists.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                                    Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>