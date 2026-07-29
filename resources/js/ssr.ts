import BaseConfirmationModal from '@/components/BaseConfirmationModal.vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { Modal, ModalLink } from '@inertiaui/modal-vue';
import { renderToString } from '@vue/server-renderer';
import 'animate.css';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, DefineComponent, h } from 'vue';
import { route as ziggyRoute } from 'ziggy-js';
import '../css/app.css';
import '../css/fonts.css';

const appName = import.meta.env.VITE_APP_NAME || 'VelnoxAICMS';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
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
        setup({ App, props, plugin }) {
            const app = createSSRApp({ render: () => h(App, props) });

            const ziggy = (page.props as any).ziggy;
            const ziggyConfig = {
                ...ziggy,
                location: new URL(ziggy?.location || 'http://localhost'),
            };

            // Create route function...
            const route = (name: string, params?: any, absolute?: boolean) =>
                ziggyRoute(name, params, absolute, ziggyConfig as any);

            // Make route function available globally...
            app.config.globalProperties.route = route as any;

            // Make route function available globally for SSR...
            if (typeof window === 'undefined') {
                (globalThis as any).route = route;
            }

            app.component('InertiaLink', Link)
                .component('Head', Head)
                .component('ModalPage', Modal)
                .component('ModalLink', ModalLink)
                .component('BaseConfirmationModal', BaseConfirmationModal)
                .use(plugin);

            return app;
        },
    }),
);
