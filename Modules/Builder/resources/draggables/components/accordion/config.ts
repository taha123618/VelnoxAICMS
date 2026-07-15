import { getId } from '@/helpers';
import accordionItemElement from '@modules/Builder/resources/draggables/components/accordion/accordion-item/config';
import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import { TElement } from '@modules/Builder/resources/scripts/types';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import ZioraElement from '@modules/Builder/resources/scripts/ziora-element';

const config: TElement = {
    id: 'accordion',
    category: 'components',
    canDrop: false,
    isLayoutElement: false,
    type: 'accordion',
    name: 'Accordion',
    icon: 'bi:chevron-bar-expand',
    props: {
        isSingle: true,

        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom),
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
                        hasUnit: true,
                        isLinked: false,
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                        unit: 'rem',
                        isFourWay: true,
                        hasUnit: true,
                        isLinked: false,
                    },
                },
                hover: {},
            },
            tablet: {
                default: {},
                hover: {},
            },
            mobile: {
                default: {},
                hover: {},
            },
        },
    },
    children: [
        new ZioraElement({
            ...accordionItemElement,
            id: getId(),
            name: 'Item 1',
        }),
        new ZioraElement({
            ...accordionItemElement,
            id: getId(),
            name: 'Item 2',
        }),
    ],
};

export default config;
