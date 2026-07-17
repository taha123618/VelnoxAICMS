import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'container',
    category: 'layout',
    type: 'container',
    name: 'Container',
    icon: 'ph:frame-corners-bold',
    canDrop: true,
    isLayoutElement: false,
    props: {
        tag: 'div',
        styles: {
            custom: deepCopy(defaultDivStyles.custom),
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    width: { value: 100, unit: '%' },
                    maxWidth: { value: 1200, unit: 'px' },
                    minHeight: { value: 50, unit: 'px' },
                    height: { value: 100, unit: 'auto' },
                    margin: { top: 0, right: 'auto', bottom: 0, left: 'auto', unit: 'custom', isFourWay: true, hasUnit: true },
                    padding: { top: 20, right: 20, bottom: 20, left: 20, unit: 'px', isFourWay: true, hasUnit: true }
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
