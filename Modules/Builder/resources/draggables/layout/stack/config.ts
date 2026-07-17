import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'stack',
    category: 'layout',
    type: 'stack',
    name: 'Stack',
    icon: 'ph:stack-bold',
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
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'flex-start',
                    alignItems: 'stretch',
                    rowGap: 16,
                    columnGap: 16,
                    width: { value: 100, unit: '%' },
                    minHeight: { value: 50, unit: 'px' }
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
