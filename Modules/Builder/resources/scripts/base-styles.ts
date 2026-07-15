import { CSSPositions } from '@modules/Builder/resources/scripts/enums';

export const defaultBackgroundStyles = {
    desktop: {
        type: 'classic', // classic, gradient
        gradientBackgroundImage: 'none',
        //color: '#FFFFFF',
        color: 'transparent',
        imageSource: null,
        imageAttachment: 'scroll', // scroll, fixed
        imagePosition: 'center center', // default, "center center", "center left", "center right",...
        imageRepeat: 'repeat', // no-repeat, repeat, repeat-x, repeat-y
        imageSize: 'auto', // auto, cover, contain
        imageBlendMode: 'normal',
    },
};

export const defaultDivStyles = {
    desktop: {
        visibility: 'visible',
        transitionTimingFunction: 'linear',
        transitionDuration: {
            value: 100,
            unit: 'ms',
        },
        scale: {
            value: 'none',
            unit: 'custom',
        },
        rotate: {
            value: 'none',
            unit: 'custom'
        },
        zIndex: {
            value: 'auto',
            unit: 'custom',
        },
        opacity: {
            value: 100,
            unit: '%',
        },
        position: CSSPositions.Static,
        top: {
            unit: 'px',
            value: 0,
        },
        right: {
            unit: 'px',
            value: 0,
        },
        bottom: {
            unit: 'px',
            value: 0,
        },
        left: {
            unit: 'px',
            value: 0,
        },
        color: '#000000',
        fontSize: {
            unit: 'px',
            value: 14,
        },
        textAlign: 'left',
        fontWeight: '400',
        webkitTextStrokeWidth: 0,
        webkitTextStrokeColor: 'transparent',
        textDecoration: 'none',
        textTransform: 'none',
        fontStyle: 'normal', // normal, italic, oblique
        fontVariant: 'normal', // small-caps, all-small-caps, petite-caps, all-petite-caps, unicase, titling-caps
        fontFamily: 'Inter',
        lineHeight: {
            unit: 'em',
            value: 1.5,
        },
        letterSpacing: 0,
        textShadow: {
            x: 0,
            y: 0,
            blur: 0,
            color: 'transparent',
        },

        borderStyle: {
            top: 'solid',
            right: 'solid',
            bottom: 'solid',
            left: 'solid',
            hasUnit: false,
            isFourWay: true,
            isLinked: false,
        },
        borderWidth: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
            unit: 'px', // custom , px, rem, em
            hasUnit: true,
            isFourWay: true,
            isLinked: false,
        },
        borderColor: {
            top: '#000000',
            right: '#000000',
            bottom: '#000000',
            left: '#000000',
            hasUnit: false,
            isFourWay: true,
            isLinked: false,
        },

        borderRadius: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
            unit: '%',
            isFourWay: true,
            isLinked: false,
            hasUnit: true,
        },

        margin: {
            top: 0,
            right: 'auto',
            bottom: 0,
            left: 'auto',
            unit: 'custom',
            isFourWay: true,
            isLinked: false,
            hasUnit: true,
        },
        padding: {
            top: 4,
            right: 4,
            bottom: 4,
            left: 4,
            unit: 'px',
            isFourWay: true,
            isLinked: false,
            hasUnit: true,
        },
        width: {
            value: 100,
            unit: '%',
        },
        minWidth: {
            value: 0,
            unit: '%',
        },
        maxWidth: {
            value: 100,
            unit: '%',
        },
        height: {
            value: 100,
            unit: 'auto',
        },
        minHeight: {
            value: 50,
            unit: 'px',
        },
        maxHeight: {
            value: 100,
            unit: '%',
        },
        boxShadow: {
            x: 0,
            y: 0,
            blur: 0,
            spread: 0,
            color: '#000000',
        },
    },
    mobile: {
        visibility: 'visible',
    },

    custom: {
        classNames: [],
        styles: '',
        animation: '',
        animationRepeat: '',
        animationDelay: '',
        animationSpeed: '',
        aos: {
            animation: false,
            easing: 'ease',
            anchorPlacement: 'top-bottom',
            once: true,
            duration: 2000,
            delay: 100,
            offset: 100,
        },
    },
};
