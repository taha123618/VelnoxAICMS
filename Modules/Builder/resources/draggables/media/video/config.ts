import { defaultBackgroundStyles, defaultDivStyles } from '@modules/Builder/resources/scripts/base-styles';
import { deepCopy } from '@modules/Builder/resources/scripts/utils';
import type { TElement } from '@modules/Builder/resources/scripts/types';

const config: TElement = {
    id: 'video',
    category: 'media',
    canDrop: false,
    isLayoutElement: false,
    type: 'video',
    name: 'Video',
    icon: 'ph:video-fill',

    props: {
        autoplay: false,
        mute: true,
        loop: false,
        controls: true,
        relatedVideos: false,
        fullscreen: false,
        startTime: '00:00',
        endTime: '00:00',
        provider: 'youtube',
        videoId: 'dQw4w9WgXcQ',
        link: '',
        aspectRatio: '', // 1:1, 3:2, 4:3, 16:9, 21:9, 9:16
        styles: {
            custom: {
                ...deepCopy(defaultDivStyles.custom)
            },
            desktop: {
                default: {
                    ...deepCopy(defaultDivStyles.desktop),
                    background: deepCopy(defaultBackgroundStyles.desktop),
                    display: 'block',
                    aspectRatio: {
                        value: '16/9',
                        unit: 'custom',
                    },
                    height: {
                        value: 100,
                        unit: '%',
                    },
                    width: {
                        value: 100,
                        unit: '%',
                    },
                },
                hover: {
                    display: 'block',
                },
                active: {
                    display: 'block',
                },
            },
            tablet: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                },
                hover: {}
            },
            mobile: {
                default: {
                    ...deepCopy(defaultDivStyles.mobile),
                },
                hover: {}
            },
        },
    },
    children: [],
};

export default config;
