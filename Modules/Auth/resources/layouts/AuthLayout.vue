<template>
    <UApp>

        <Head :title="title" />
        <div class="flex min-h-full flex-col justify-center bg-neutral-100 dark:bg-neutral-950 px-6 py-12 lg:px-8">
            <UCard :ui="{
                root: 'sm:mx-auto rounded-none sm:w-full sm:max-w-sm',
                header: 'sm:mx-auto relative border-none',
                body: 'p-4 sm:px-6 sm:pt-0'
            }">
                <template #header>
                    <div class="flex justify-center items-center">
                        <img
                            class="h-14 w-auto dark:hidden"
                            src="/assets/images/logo-full-black.svg"
                            alt="Ziora CMS"
                        />
                        <img
                            class="h-14 w-auto hidden dark:block"
                            src="/assets/images/logo-full-white.svg"
                            alt="Ziora CMS"
                        />
                    </div>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p
                            v-if="description"
                            class="text-center text-sm text-neutral-500 dark:text-neutral-400"
                        >{{ description }}</p>
                    </div>
                </template>
                <slot />
            </UCard>
        </div>
    </UApp>
</template>

<script setup lang="ts">
import { useNotification } from '@/composables/use-notification';

defineProps<{
    title: string;
    description?: string;
}>();

useNotification();

</script>
