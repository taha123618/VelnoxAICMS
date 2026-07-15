import { defaultDivStyles } from "@modules/Builder/resources/scripts/base-styles";
import { TElement } from "@modules/Builder/resources/scripts/types";
import { deepCopy } from "@modules/Builder/resources/scripts/utils";


const config: TElement = {
    id: 'content',
    name: 'Content',
    canDrop: false,
    isLayoutElement: true,
    type: 'content',
    icon: 'ph:square-fill',
    props: {
        tag: 'div',
        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom)
            },
            desktop: {
                default: {
                    display: 'block',
                },
                hover: {
                    display: 'block',
                }
            },
            tablet: {
                default: {
                    display: 'block',
                },
                hover: {
                    display: 'block',
                }
            },
            mobile: {
                default: {
                    display: 'block',
                },
                hover: {
                    display: 'block',
                }
            },
        },
    },
    children: [],
};

export default config;
