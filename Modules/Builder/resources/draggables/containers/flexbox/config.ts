import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';
const config: TElement = {
    id: 'flexbox',
    category: 'containers',
    canDrop: true,
    isLayoutElement: false,
    type: 'flexbox',
    name: 'Flex box',
    icon: 'ph:rectangle-dashed-fill',
    props: {
        tag: 'div',
        styles: {
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    display: 'flex',
                    flexDirection: 'row',
                    justifyContent: 'flex-start',
                    alignItems: 'center',
                    columnGap: 10,
                    rowGap: 10,
                    flexWrap: 'nowrap',
                    gridColumn: 1,
                    gridRow: 1,
                },
                hover: {
                    display: 'flex',
                }
            },
            tablet: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                    flexDirection: 'column',
                },
                hover: {
                    display: 'flex',
                }
            },
            mobile: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                    flexDirection: 'column',
                },
                hover: {
                    display: 'flex',
                }
            },
            custom: {
                ...deepCopy(defaultDivStyles.custom)
            },
        },
    },
    children: [],
};

export default config;
