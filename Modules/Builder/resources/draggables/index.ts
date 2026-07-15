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
        id: 'containers',
        name: 'Containers',
        components: configs.filter((x) => x.category == 'containers'),
    },
    {
        id: 'typography',
        name: 'Typography',
        components: configs.filter((x) => x.category == 'typography'),
    },
    {
        id: 'media',
        name: 'Media',
        components: configs.filter((x) => x.category == 'media'),
    },
    {
        id: 'components',
        name: 'Components',
        components: configs.filter((x) => x.category == 'components'),
    },
    {
        id: 'forms',
        name: 'Forms',
        components: configs.filter((x) => x.category == 'forms'),
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
