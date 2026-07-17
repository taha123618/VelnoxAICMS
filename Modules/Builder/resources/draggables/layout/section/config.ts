import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'section',
    category: 'layout',
    type: 'section',
    name: 'Section',
    icon: 'ph:layout-bold',
    canDrop: true,
    isLayoutElement: false,
    props: {
        tag: 'section',
        styles: {
            custom: deepCopy(defaultDivStyles.custom),
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    width: { value: 100, unit: '%' },
                    minHeight: { value: 100, unit: 'px' },
                    height: { value: 100, unit: 'auto' },
                    padding: { top: 60, right: 20, bottom: 60, left: 20, unit: 'px', isFourWay: true, hasUnit: true }
                },
                hover: {}
            },
            tablet: { default: deepCopy(defaultDivStyles.mobile), hover: {} },
            mobile: { default: deepCopy(defaultDivStyles.mobile), hover: {} }
        }
    },
    children: []
};

export default config;
