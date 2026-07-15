import {
    defaultBackgroundStyles,
    defaultDivStyles,
} from '@modules/Builder/resources/scripts/base-styles';
import { TElement } from '@modules/Builder/resources/scripts/types';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';

const config: TElement = {
    id: 'navigation',
    category: 'components',
    canDrop: false,
    isLayoutElement: false,
    type: 'navigation',
    name: 'Navbar',
    icon: 'carbon:list-dropdown',
    props: {
        menuId: null,
        logo: {
            display: true,
            src: '',
            height: {
                value: 60,
                unit: 'px',
            },
            width: {
                value: 100,
                unit: 'auto',
            },
        },
        menuListItem:{
            display: 'inline-block',
            position: 'relative',
        },
        menuListLink: {
            color: '#ffffff',
            hoverColor: '#ffffff',
            hoverBackgroundColor: '#1D8549',
            textTransform: 'uppercase',
            fontFamily: 'Inter',
            fontSize: 14,
            lineHeight: 45,
            fontWeight: 700,
            display: 'block',
            paddingY: 0,
            paddingX: 16
        },

        dropdown: {
            hoverDisplay: 'block',
            show: false,
            fontWeight: 400,
            fontSize: 14,
            fontFamily: 'Inter',
            textTransform: 'none',
            color: '#ffffff',
            hoverColor: '#ffffff',
            backgroundColor: '#9b59b6',
            hoverBackgroundColor: 'rgba(0, 0, 0, 0.1)',
            borderRadius: 0,
            borderWidth: 0,
            borderStyle: 'solid',
            borderColor: '#dddddd'
        },
        toggleButton:{
            label: 'MENU',
            icon: 'ph:list',
            backgroundColor: 'transparent',
            fontSize: 20,
            color: '#ffffff',
            paddingX: 6,
            paddingY: 6,
            borderRadius: 0,
            height: 45
        },
        mobileMenu: {
            side: 'left',
            backgroundColor: '#ffffff',
            color: '#000',
            fontSize: 14,
            closeButtonColor: 'primary',
            closeButtonVariant: 'subtle',
            closeButtonSize: 'sm',
            menuTitle: 'MENU',
        },
        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom),
            },
            desktop: {
                default: {
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    columnGap: 10,
                    rowGap: 10,
                    ...deepCopy(defaultDivStyles.desktop),
                    background: {
                        ...deepCopy(defaultBackgroundStyles.desktop),
                        color: '#1e8449',
                    },
                    height: {
                        value: 60,
                        unit: 'px',
                    },
                    color: '#ffffff',
                    margin: {
                        top: '0',
                        right: 'auto',
                        bottom: '0',
                        left: 'auto',
                        unit: 'custom',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
                    padding: {
                        top: 0,
                        right: 4,
                        bottom: 0,
                        left: 4,
                        unit: 'rem',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
                },
                hover: {},
            },
            tablet: {
                default: {
                    padding: {
                        top: 0,
                        right: 1,
                        bottom: 0,
                        left: 1,
                        unit: 'rem',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
                },
                hover: {},
            },
            mobile: {
                default: {
                    padding: {
                        top: 0,
                        right: 1,
                        bottom: 0,
                        left: 1,
                        unit: 'rem',
                        isFourWay: true,
                        isLinked: false,
                        hasUnit: true,
                    },
                },
                hover: {},
            },
        },
    },
    children: [],
};

export default config;
