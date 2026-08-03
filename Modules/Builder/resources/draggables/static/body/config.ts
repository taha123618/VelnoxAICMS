import { TElement } from '@modules/Builder/resources/scripts/types';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import contentElement from '@modules/Builder/resources/draggables/static/content/config';

const config: TElement = {
    id: 'el__body',
    name: 'Body',
    canDrop: true,
    isLayoutElement: true,
    type: 'body',
    icon: 'ph:square',
    children: [new VelnoxAIElement(contentElement)],
    props: {
        tag: 'div',
        styles: {
            custom: {},
            desktop: {
                default: {
                    display: 'block',
                },
                hover: {
                    display: 'block',
                },
            },
            tablet: {
                default: {
                    display: 'block',
                },
                hover: {
                    display: 'block',
                },
            },
            mobile: {
                default: {
                    display: 'block',
                },
                hover: {
                    display: 'block',
                },
            },
        },
    },
};

export default config;
