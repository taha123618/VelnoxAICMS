import ui from '@nuxt/ui/vite';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import path from 'path';
import { defineConfig } from 'vite';
import { watch } from 'vite-plugin-watch';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/fonts.css',
                'resources/js/app.ts',
            ],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        ui({
            inertia: true,
            autoImport: {
                vueTemplate: true,
                imports: [
                    'vue',
                    '@vueuse/core',
                    {
                        '@inertiajs/vue3': [
                            'router',
                            'useForm',
                            'usePage',
                            'useRemember',
                        ],
                    },
                ],
            },
            ui: {
                colors: {
                    primary: 'indigo',
                },
                button: {
                    slots: {
                        base: 'cursor-pointer',
                    },
                },
                navigationMenu: {
                    slots: {
                        label: 'text-sm text-white',
                    },
                    compoundVariants: [
                        {
                            color: 'primary',
                            variant: 'pill',
                            active: true,
                            class: {
                                link: 'text-primary-300',
                                linkLeadingIcon:
                                    'text-primary-300 group-data-[state=open]:text-primary-300',
                            },
                        },
                        {
                            variant: 'pill',
                            active: true,
                            highlight: false,
                            class: {
                                link: 'before:bg-neutral-700',
                            },
                        },
                        {
                            disabled: false,
                            active: false,
                            variant: 'pill',
                            class: {
                                link: [
                                    'hover:text-white hover:before:bg-neutral-700 hover:text-white focus-visible:before:ring-1',
                                    'transition-colors before:transition-colors text-white',
                                ],
                                linkLeadingIcon: [
                                    'group-hover:text-white',
                                    'transition-colors',
                                ],
                            },
                        },
                        {
                            orientation: 'vertical',
                            collapsed: false,
                            class: {
                                childList:
                                    'ms-5 border-s border-dashed border-neutral-500',
                                childItem: 'ps-1.5 -ms-px',
                            },
                        },
                    ],
                },
                tooltip: {
                    slots: {
                        content: '!z-[100]',
                    },
                },
                modal: {
                    slots: {
                        overlay: 'bg-neutral-900/75 backdrop-blur-sm',
                    },
                },
                slideover: {
                    slots: {
                        overlay: 'bg-neutral-900/75 backdrop-blur-sm',
                    },
                },
                popover: {
                    slots: {
                        content:
                            '!z-[100] bg-(--ui-bg) shadow-lg rounded-[calc(var(--ui-radius)*1.5)] ring ring-(--ui-border) data-[state=open]:animate-[scale-in_100ms_ease-out] data-[state=closed]:animate-[scale-out_100ms_ease-in] focus:outline-none pointer-events-auto',
                    },
                },
                dropdownMenu: {
                    slots: {
                        content: 'divide-none',
                    },
                },
                select: {
                    slots: {
                        content: '!z-[101]',
                    },
                },
                selectMenu: {
                    slots: {
                        content: '!z-[101]',
                    },
                },
                table: {
                    slots: {
                        th: 'px-3 py-2',
                        td: 'px-3 py-2 dark:text-white text-black',
                    },
                },
                contextMenu: {
                    slots: {
                        content: '!z-[101]',
                    },
                },
                input: {
                    compoundVariants: [
                        {
                            color: 'primary',
                            class: 'focus-visible:ring-1 focus-visible:ring-inset focus-visible:ring-(--ui-primary)',
                        },
                        {
                            color: 'neutral',
                            class: 'focus-visible:ring-1 focus-visible:ring-inset focus-visible:ring-(--ui-border-inverted)',
                        },
                    ],
                },
                textarea: {
                    compoundVariants: [
                        {
                            color: 'primary',
                            class: 'focus-visible:ring-1 focus-visible:ring-inset focus-visible:ring-(--ui-primary)',
                        },
                        {
                            color: 'neutral',
                            class: 'focus-visible:ring-1 focus-visible:ring-inset focus-visible:ring-(--ui-border-inverted)',
                        },
                    ],
                },
                inputNumber: {
                    compoundVariants: [
                        {
                            color: 'primary',
                            variant: ['outline', 'subtle'],
                            class: 'focus-visible:ring-1',
                        },
                    ],
                },
            },
        }),

        watch({
            pattern: '{app,Modules}/**/{Data,Enums}/**/*.php',
            command: 'php artisan typescript:transform',
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '@modules': path.resolve(__dirname, './Modules'),
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
    // server: {
    //     hmr: {
    //         host: process.env.CODESPACE_NAME
    //             ? process.env.CODESPACE_NAME +
    //               '-5173.' +
    //               process.env.GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN
    //             : undefined,
    //         clientPort: process.env.CODESPACE_NAME ? 443 : undefined,
    //         protocol: process.env.CODESPACE_NAME ? 'wss' : undefined,
    //     },
    // },
});
