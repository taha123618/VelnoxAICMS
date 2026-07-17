import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'button-group',
    category: 'interactive',
    type: 'button-group',
    name: 'Button Group',
    icon: 'ph:circles-three-bold',
    canDrop: true,
    isLayoutElement: false,
    props: {
        styles: {
            custom: deepCopy(defaultDivStyles.custom),
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    display: 'flex',
                    flexDirection: 'row',
                    alignItems: 'center',
                    justifyContent: 'flex-start',
                    columnGap: 12,
                    rowGap: 12,
                    width: { value: 100, unit: '%' }
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
