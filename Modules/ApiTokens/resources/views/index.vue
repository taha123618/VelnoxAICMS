<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@modules/Dashboard/resources/layouts/AdminLayout.vue';
import { ref } from 'vue';
import dayjs from 'dayjs';
import type { SharedData } from '@/types';

const props = defineProps<{
    tokens: any[]
}>();

const page = usePage<SharedData>();

const form = useForm({
    name: '',
});

const createToken = () => {
    form.post(route('api-tokens.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('name'),
    });
};

const revokeToken = (id: number) => {
    if (confirm('Are you sure you want to revoke this token?')) {
        useForm({}).delete(route('api-tokens.destroy', id), {
            preserveScroll: true,
        });
    }
};

const formatDate = (dateString: string) => {
    return dayjs(dateString).format('MMM D, YYYY h:mm A');
};

</script>

<template>
    <Head title="API Tokens" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                API Tokens
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Display New Token (if just created) -->
                <div v-if="page.props.flash?.token" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Token Created!</strong>
                    <span class="block sm:inline ml-2">{{ page.props.flash?.message }}</span>
                    <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded font-mono text-sm break-all text-gray-900 dark:text-gray-100 border">
                        {{ page.props.flash?.token }}
                    </div>
                </div>

                <!-- Create Token Form -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Create New Token</h3>
                    <form @submit.prevent="createToken" class="flex items-end gap-4">
                        <div class="flex-1">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Token Name</label>
                            <input type="text" id="name" v-model="form.name" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100">
                            <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>
                        <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                            Create Token
                        </button>
                    </form>
                </div>

                <!-- Existing Tokens List -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Active Tokens</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Used</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="token in (tokens || [])" :key="token.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ token.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ token.last_used_at ? formatDate(token.last_used_at) : 'Never' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ formatDate(token.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="revokeToken(token.id)" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">Revoke</button>
                                        </td>
                                    </tr>
                                    <tr v-if="!tokens || tokens.length === 0">
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No active API tokens found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>