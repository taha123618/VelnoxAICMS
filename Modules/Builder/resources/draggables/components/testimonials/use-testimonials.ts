import { useElement } from '@modules/Builder/resources/scripts/use-element';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import axios from 'axios';

export const useTestimonials = (element: VelnoxAIElement) => {
    const active = ref(0);
    const items = ref<Modules.Testimonial.Data.TestimonialData[]>([]);
    const { className } = useElement(element);

    const interval = ref<any>();

    const activeTestimonialComment = computed(() => {
        if (items.value.length > 0) {
            return items.value[active.value].comment.split(' ');
        }
    });

    function handleNext() {
        active.value = (active.value + 1) % items.value.length;
    }

    function handlePrev() {
        active.value =
            (active.value - 1 + items.value.length) % items.value.length;
    }

    function isActive(index: number) {
        return active.value === index;
    }

    function randomRotateY() {
        return -4;
        // return Math.floor(Math.random() * 21) - 10;
    }

    function getStyles() {
        return ` 
            .${className.value} {
                .img{
                    border-radius: ${element.getProp('image.borderRadius')}px;
                    width: ${element.getProp('image.width.unit') == 'auto' ? 'auto' : element.getProp('image.width.value') + element.getProp('image.width.unit')};
                    height: ${element.getProp('image.height.unit') == 'auto' ? 'auto' : element.getProp('image.height.value') + element.getProp('image.height.unit')};
                }
                .avatar{
                    width: ${element.getProp('avatar.width.unit') == 'auto' ? 'auto' : element.getProp('avatar.width.value') + element.getProp('avatar.width.unit')};
                    height: ${element.getProp('avatar.height.unit') == 'auto' ? 'auto' : element.getProp('avatar.height.value') + element.getProp('avatar.height.unit')};
                }
                .heading {
                    font-size: ${element.getProp('heading.fontSize')}px;
                    color: ${element.getProp('heading.color')};
                    font-weight: ${element.getProp('heading.fontWeight')};
                    font-family: ${element.getProp('heading.fontFamily')};
                }
                .subheading {
                    font-size: ${element.getProp('subHeading.fontSize')}px;
                    color: ${element.getProp('subHeading.color')};
                    font-weight: ${element.getProp('subHeading.fontWeight')};
                    font-family: ${element.getProp('subHeading.fontFamily')};
                }
                .body {
                    font-size: ${element.getProp('body.fontSize')}px;
                    color: ${element.getProp('body.color')};
                    font-weight: ${element.getProp('body.fontWeight')};
                    font-family: ${element.getProp('body.fontFamily')};
                }
        }`;
    }

    watchEffect(() => {
        axios
            .get(route('api.testimonials.index'))
            .then((response) => {
                items.value = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
    });

    onMounted(() => {
        if (element.getProp('autoplay')) {
            interval.value = setInterval(
                handleNext,
                element.getProp('duration'),
            );
        }
    });

    onUnmounted(() => {
        if (!interval.value) {
            clearInterval(interval.value);
        }
    });

    return {
        handlePrev,
        handleNext,
        interval,
        randomRotateY,
        isActive,
        items,
        active,
        getStyles,
        activeTestimonialComment,
    };
};
