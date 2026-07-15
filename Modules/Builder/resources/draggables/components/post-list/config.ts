import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';
const config: TElement = {
    id: 'post-list',
    category: 'components',
    canDrop: false,
    isLayoutElement: false,
    type: 'post-list',
    name: 'Post list',
    icon: 'ph:push-pin',
    props: {
        container: {
            columns: 3,
            columnGap: 10,
            rowGap: 10,
        },
        img: {
            show: true,
            height: {
                value: 200,
                unit: 'px',
            },
            width: {
                value: 100,
                unit: '%',
            },
        },
        item: {
            skin: 'cards', // 'cards', 'plain', list
            borderRadius: 10,
            borderWidth: 0,
            borderStyle: 'solid',
            borderColor: '#000000',
            backgroundColor: 'transparent',
        },
        metabox: {
            padding: 0,
        },
        category:{
            show: true,
        },
        title: {
            show: true,
            fontSize: 18,
            fontFamily: 'Inter',
            fontWeight: 700,
            color: '#000',
        },
        excerpt: {
            show: true,
            fontSize: 13,
            fontFamily: 'Inter',
            fontWeight: 400,
            color: '#000',
        },
        link: {
            autoLayout: true,
            show: true,
            target: '_self',
            text: 'Read more...',
            fontSize: 13,
            fontFamily: 'Inter',
            fontWeight: 400,
            color: '#6409ea',
            backgroundColor: 'transparent',
            borderRadius: 0,
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0,
                unit: 'px',
                hasUnit: true
            },
        },
        query: {
            selection: 'automatic', // automatic, manual
            fromCategories: false,
            orderBy: 'created_at', // title, author, updated_at, random
            orderDir: 'desc',
            perPage: 3,
            categories: [],
            itemIds: [],
            showPagination: false,
        },
        styles: {
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                },
                hover: {},
            },
            tablet: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                },
                hover: {},
            },
            mobile: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                },
                hover: {},
            },
            custom: {
                ...deepCopy(defaultDivStyles.custom)
            },
        },
    },
    children: [],
};

export default config;
