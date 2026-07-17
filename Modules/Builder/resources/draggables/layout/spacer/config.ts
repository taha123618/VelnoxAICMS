import { defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'spacer',
    category: 'layout',
    type: 'spacer',
    name: 'Spacer',
    icon: 'ph:arrows-out-line-vertical-bold',
    canDrop: false,
    isLayoutElement: false,
    props: {
        styles: {
            custom: deepCopy(defaultDivStyles.custom),
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    minHeight: { value: 0, unit: 'px' },
                    height: { value: 50, unit: 'px' },
                    width: { value: 100, unit: '%' },
                    padding: { top: 0, right: 0, bottom: 0, left: 0, unit: 'px', isFourWay: true, hasUnit: true }
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
