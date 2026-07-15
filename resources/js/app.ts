import BaseConfirmationModal from '@/components/BaseConfirmationModal.vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { Modal, ModalLink, renderApp } from '@inertiaui/modal-vue';
import ui from '@nuxt/ui/vue-plugin';
import { createManager } from '@vue-youtube/core';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import type { DefineComponent } from 'vue';
import { createApp } from 'vue';
import { ZiggyVue } from 'ziggy-js';

import 'animate.css';
import '../css/app.css';
import '../css/fonts.css';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'VelnoxAICMS';
const pinia = createPinia();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const isModule = name.split('::');
        if (isModule.length > 1) {
            const pages = import.meta.glob<{ default: DefineComponent }>([
                './pages/**/*.vue',
                '../../Modules/*/resources/views/**/*.vue',
            ]);

            const regex = /([^:]+)::(.+)/;
            const matches = regex.exec(name);
            let page;
            if (matches && matches.length > 2) {
                const module = matches[1];
                const pageName = matches[2];
                page =
                    pages[
                    `../../Modules/${module}/resources/views/${pageName}.vue`
                    ];
            } else {
                page = pages[`./pages/${name}.vue`];
            }

            if (!page) {
                throw new Error(`Page not found: ${name}`);
            }
            return (await page()).default;
        } else {
            return resolvePageComponent(
                `./pages/${name}.vue`,
                import.meta.glob<DefineComponent>('./pages/**/*.vue'),
            );
        }
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: renderApp(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia)
            .use(ui)
            .use(createManager())
            .component('Link', Link)
            .component('Head', Head)
            .component('BaseConfirmationModal', BaseConfirmationModal)
            .component('ModalPage', Modal)
            .component('ModalLink', ModalLink)
            .mount(el)
            .$nextTick(() => {
                delete (el as Element as HTMLElement).dataset.page;
            });
    },
    progress: {
        color: '#4B5563',
    },
});
