import AnimationSettings from '@modules/Builder/resources/components/settings/animation-settings.vue';
import BorderStyleSettings from '@modules/Builder/resources/components/settings/border-style-settings.vue';
import CustomStyleSettings from '@modules/Builder/resources/components/settings/custom-style-settings.vue';
import DecorationSettings from '@modules/Builder/resources/components/settings/decoration-settings.vue';
import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import PositionSettings from '@modules/Builder/resources/components/settings/position-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import TransformSettings from '@modules/Builder/resources/components/settings/transform-settings.vue';
import TransitionSettings from '@modules/Builder/resources/components/settings/transition-settings.vue';
import ImageContentSettings from '@modules/Builder/resources/draggables/media/image/components/image-content-settings.vue';
import Image from '@modules/Builder/resources/draggables/media/image/image.vue';
import Render from '@modules/Builder/resources/draggables/media/image/render.vue';

export default {
    component: Image,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Image', component: ImageContentSettings },
        { name: 'Size', component: DimensionSettings },
        { name: 'Spacing', component: SpacingSettings },
        { name: 'Position', component: PositionSettings },
        { name: 'Border', component: BorderStyleSettings },
        { name: 'Decoration', component: DecorationSettings },
        { name: 'Custom styling', component: CustomStyleSettings },
        { name: 'Animations', component: AnimationSettings },
        { name: 'Transformations', component: TransformSettings },
        { name: 'Transitions', component: TransitionSettings },
    ],
};
