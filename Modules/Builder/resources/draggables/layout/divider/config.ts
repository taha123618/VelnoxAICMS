import { defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'divider',
    category: 'layout',
    type: 'divider',
    name: 'Divider',
    icon: 'ph:minus-bold',
    canDrop: false,
    isLayoutElement: false,
    props: {
        styles: {
            custom: deepCopy(defaultDivStyles.custom),
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    minHeight: { value: 0, unit: 'px' },
                    height: { value: 1, unit: 'px' },
                    width: { value: 100, unit: '%' },
                    padding: { top: 16, right: 0, bottom: 16, left: 0, unit: 'px', isFourWay: true, hasUnit: true },
                    borderWidth: { top: 0, right: 0, bottom: 1, left: 0, unit: 'px', isFourWay: true, hasUnit: true },
                    borderColor: { top: '#000000', right: '#000000', bottom: '#e5e7eb', left: '#000000', hasUnit: false, isFourWay: true },
                    borderStyle: { top: 'none', right: 'none', bottom: 'solid', left: 'none', hasUnit: false, isFourWay: true }
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
