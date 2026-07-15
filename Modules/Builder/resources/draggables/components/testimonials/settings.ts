import AnimationSettings from '@modules/Builder/resources/components/settings/animation-settings.vue';
import BackgroundSettings from '@modules/Builder/resources/components/settings/background-settings.vue';
import BorderStyleSettings from '@modules/Builder/resources/components/settings/border-style-settings.vue';
import DecorationSettings from '@modules/Builder/resources/components/settings/decoration-settings.vue';
import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import PositionSettings from '@modules/Builder/resources/components/settings/position-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import TransitionSettings from '@modules/Builder/resources/components/settings/transition-settings.vue';
import TestimonialHeadingSettings from '@modules/Builder/resources/draggables/components/testimonials/components/testimonial-heading-settings.vue';
import TestimonialImageSettings from '@modules/Builder/resources/draggables/components/testimonials/components/testimonial-image-settings.vue';
import TestimonialQuoteSettings from '@modules/Builder/resources/draggables/components/testimonials/components/testimonial-quote-settings.vue';
import TestimonialSettings from '@modules/Builder/resources/draggables/components/testimonials/components/testimonial-settings.vue';
import TestimonialSubheadingSettings from '@modules/Builder/resources/draggables/components/testimonials/components/testimonial-subheading-settings.vue';
import Render from '@modules/Builder/resources/draggables/components/testimonials/render.vue';
import Testimonials from '@modules/Builder/resources/draggables/components/testimonials/testimonials.vue';

export default {
    component: Testimonials,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Testimonials', component: TestimonialSettings },
        { name: 'Image', component: TestimonialImageSettings },
        { name: 'Quote', component: TestimonialQuoteSettings },
        { name: 'Name', component: TestimonialHeadingSettings },
        { name: 'Title', component: TestimonialSubheadingSettings },
        { name: 'Background', component: BackgroundSettings },
        { name: 'Sizing', component: DimensionSettings },
        { name: 'Spacing', component: SpacingSettings },
        { name: 'Border', component: BorderStyleSettings },
        { name: 'Position', component: PositionSettings },
        { name: 'Decoration', component: DecorationSettings },
        { name: 'Animation', component: AnimationSettings },
        { name: 'Transition', component: TransitionSettings },
    ],
};
