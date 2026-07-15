import AnimationSettings from '@modules/Builder/resources/components/settings/animation-settings.vue';
import BackgroundSettings from '@modules/Builder/resources/components/settings/background-settings.vue';
import BorderStyleSettings from '@modules/Builder/resources/components/settings/border-style-settings.vue';
import CustomStyleSettings from '@modules/Builder/resources/components/settings/custom-style-settings.vue';
import DecorationSettings from '@modules/Builder/resources/components/settings/decoration-settings.vue';
import DimensionSettings from '@modules/Builder/resources/components/settings/dimension-settings.vue';
import ElementInfoSettings from '@modules/Builder/resources/components/settings/element-info-settings.vue';
import PositionSettings from '@modules/Builder/resources/components/settings/position-settings.vue';
import SpacingSettings from '@modules/Builder/resources/components/settings/spacing-settings.vue';
import TransformSettings from '@modules/Builder/resources/components/settings/transform-settings.vue';
import TransitionSettings from '@modules/Builder/resources/components/settings/transition-settings.vue';
import VideoSettings from '@modules/Builder/resources/draggables/media/video/components/video-settings.vue';
import Render from '@modules/Builder/resources/draggables/media/video/render.vue';
import Video from '@modules/Builder/resources/draggables/media/video/video.vue';

export default {
    component: Video,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Video', component: VideoSettings },
        { name: 'Size', component: DimensionSettings },
        { name: 'Position', component: PositionSettings },
        { name: 'Spacing', component: SpacingSettings },
        { name: 'Background', component: BackgroundSettings },
        { name: 'Border', component: BorderStyleSettings },
        { name: 'Decoration', component: DecorationSettings },
        { name: 'Animations', component: AnimationSettings },
        { name: 'Transformations', component: TransformSettings },
        { name: 'Transitions', component: TransitionSettings },
        { name: 'Custom styling', component: CustomStyleSettings },
    ],
};
