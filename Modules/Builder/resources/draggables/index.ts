import {
    TElement,
    TElementGroup,
} from '@modules/Builder/resources/scripts/types';
import { getBaseFolderName } from '@modules/Builder/resources/scripts/utils';

const configModules = import.meta.glob('./**/*/config.ts', { eager: true });

const configs = Object.entries(configModules).map(([, module]) => {
    return {
        ...((module as unknown as Record<string, any>).default as TElement),
    };
});

export const elementGroups: TElementGroup[] = [
    {
        id: 'layout',
        name: 'Layout',
        components: configs.filter((x) => x.category == 'layout' || x.category == 'containers' || x.type == 'accordion' || x.type == 'tabs' || x.type == 'navigation'),
    },
    {
        id: 'content',
        name: 'Content',
        components: configs.filter((x) => x.category == 'content' || x.category == 'typography'),
    },
    {
        id: 'media',
        name: 'Media',
        components: configs.filter((x) => x.category == 'media' && x.type !== 'avatar'),
    },
    {
        id: 'interactive',
        name: 'Interactive',
        components: configs.filter((x) => x.category == 'interactive'),
    },
    {
        id: 'forms',
        name: 'Forms',
        components: configs.filter((x) => x.category == 'forms'),
    },
    {
        id: 'marketing',
        name: 'Marketing',
        components: configs.filter((x) => x.category == 'marketing' || x.type == 'testimonials'),
    },
    {
        id: 'ecommerce',
        name: 'E-commerce',
        components: configs.filter((x) => x.category == 'ecommerce'),
    },
    {
        id: 'social',
        name: 'Social',
        components: configs.filter((x) => x.category == 'social' || x.type == 'avatar'),
    },
    {
        id: 'blog',
        name: 'Blog',
        components: configs.filter((x) => x.category == 'blog' || x.type == 'post-list'),
    },
    {
        id: 'advanced',
        name: 'Advanced',
        components: configs.filter((x) => x.category == 'advanced'),
    },
];

const settingsModules = import.meta.glob('./**/*/settings.ts', { eager: true });

export const elementSettings = Object.entries(settingsModules).map(
    ([path, module]) => {
        return {
            path,
            type: getBaseFolderName(path),
            component: (module as unknown as Record<string, any>).default
                .component,
            renderable: (module as unknown as Record<string, any>).default
                .renderable,
            settings: (module as unknown as Record<string, any>).default
                .settings,
        };
    },
);
