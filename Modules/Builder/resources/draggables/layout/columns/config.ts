import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'columns',
    category: 'layout',
    type: 'columns',
    name: 'Columns',
    icon: 'ph:columns-bold',
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
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    alignItems: 'stretch',
                    columnGap: 20,
                    rowGap: 20,
                    flexWrap: 'nowrap',
                    width: { value: 100, unit: '%' },
                    minHeight: { value: 50, unit: 'px' }
                },
                hover: {}
            },
            tablet: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                    flexDirection: 'column'
                },
                hover: {}
            },
            mobile: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                    flexDirection: 'column'
                },
                hover: {}
            }
        }
    },
    children: []
};

export default config;
