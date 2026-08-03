import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import { TElement } from '@modules/Builder/resources/scripts/types';
import tabsItemElement from '@modules/Builder/resources/draggables/components/tabs/tabs-item/config';
import { getId } from '@/helpers';

const config: TElement = {
    id: 'tabs',
    category: 'components',
    canDrop: false,
    isLayoutElement: false,
    type: 'tabs',
    name: 'Tabs',
    icon: 'ph:tabs',
    props: {
        isSingle: true,
        orientation: 'horizontal',
        variant: 'link', // pill / link
        size: 'md', // "xs" | "md" | "sm" | "lg" | "xl"

        trigger: {
            backgroundColor: '#000000',
            activeColor: '#ffffff',
            color: '#000000',
            fontSize: 14,
            fontWeight: 400,
            fontFamily: 'Inter'
        },
        indicator: {
            backgroundColor: '#000000'
        },

        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom)
            },
            desktop: {
                default: {
                    display: 'block',
                    ...deepCopy(defaultDivStyles.desktop),
                    background: {
                        ...deepCopy(defaultBackgroundStyles.desktop),
                    },
                    margin: {
                        top: 'auto',
                        right: 'auto',
                        bottom: 'auto',
                        left: 'auto',
                        unit: 'custom',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                        unit: 'rem',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
                },
                hover: {}
            },
            tablet: {
                default: {},
                hover: {}
            },
            mobile: {
                default: {},
                hover: {}
            },
        },
    },
    children: [
        new VelnoxAIElement({
            ...tabsItemElement,
            id: getId(),
            name: 'Item 1',
        }),
        new VelnoxAIElement({
            ...tabsItemElement,
            id: getId(),
            name: 'Item 2',
        }),
    ],
};

export default config;
