<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome to ZioraCMS">
        <link
            rel="preconnect"
            href="https://rsms.me/" />
        <link
            rel="stylesheet"
            href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#f4f4f4] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-neutral-800">
        <div
            class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
            <main
                class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <UCard
                    class="lg:rounded-r-none"
                    :ui="{
                        body: 'flex-1  h-full rounded-br-lg rounded-bl-lg p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:rounded-tl-lg lg:rounded-br-none lg:p-20 dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]',
                    }">
                    <h1
                        class="animate-in slide-in-from-top mb-1 text-lg font-medium">
                        Welcome to ZioraCMS
                    </h1>
                    <p class="mb-2 text-neutral-500 dark:text-neutral-400">
                        This site is still under construction.<br />
                        Something awesome will be coming soon.
                    </p>
                    <div class="my-5">
                        Visit the documentation to see how to get started.
                    </div>
                    <div class="flex gap-3 text-sm leading-normal">
                        <Link :href="route('admin.login')">
                            <UButton
                                label="Login and start building" />
                        </Link>
                        <UButton
                            color="secondary"
                            variant="subtle"
                            label="Go to documentation"
                            href="https://zioracms.com" />
                    </div>
                </UCard>

                <UCard
                    class="lg:rounded-l-none"
                    :ui="{
                        body: 'relative h-full -mb-px flex flex-col items-center justify-center aspect-335/376 w-full shrink-0 overflow-hidden rounded-t-lg bg-indigo-700 lg:mb-0 lg:-ml-px lg:aspect-auto lg:w-[438px] lg:rounded-t-none lg:rounded-r-lg dark:bg-[#1D0002]',
                    }">
                    <img
                        src="/assets/images/logo-full-white.svg"
                        class="translate-y-0 transition-all duration-750 starting:-translate-y-6 starting:opacity-0" />
                    <h1
                        class="translate-y-0 text-xl font-light text-white transition-all duration-750 starting:translate-y-6 starting:opacity-0">
                        The Laravel Drag-and-drop Page Builder
                    </h1>

                    <div
                        class="absolute inset-0 rounded-t-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:overflow-hidden lg:rounded-t-none lg:rounded-r-lg dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]" />
                </UCard>
            </main>
        </div>
    </div>
</template>
