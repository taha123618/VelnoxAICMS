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
import PostListContainerSettings from '@modules/Builder/resources/draggables/components/post-list/components/post-list-container-settings.vue';
import PostListExcerptSettings from '@modules/Builder/resources/draggables/components/post-list/components/post-list-excerpt-settings.vue';
import PostListImageSettings from '@modules/Builder/resources/draggables/components/post-list/components/post-list-image-settings.vue';
import PostListLinkSettings from '@modules/Builder/resources/draggables/components/post-list/components/post-list-link-settings.vue';
import PostListQuerySettings from '@modules/Builder/resources/draggables/components/post-list/components/post-list-query-settings.vue';
import PostListTitleSettings from '@modules/Builder/resources/draggables/components/post-list/components/post-list-title-settings.vue';
import PostListWrapperSettings from '@modules/Builder/resources/draggables/components/post-list/components/post-list-wrapper-settings.vue';
import PostList from '@modules/Builder/resources/draggables/components/post-list/post-list.vue';
import Render from '@modules/Builder/resources/draggables/components/post-list/render.vue';

export default {
    component: PostList,
    renderable: Render,
    settings: [
        { name: 'Info', component: ElementInfoSettings },
        { name: 'Query', component: PostListQuerySettings },
        { name: 'Container', component: PostListContainerSettings },
        { name: 'Item Wrapper', component: PostListWrapperSettings },
        { name: 'Image', component: PostListImageSettings },
        { name: 'Title', component: PostListTitleSettings },
        { name: 'Excerpt', component: PostListExcerptSettings },
        { name: 'Link', component: PostListLinkSettings },
        { name: 'Size', component: DimensionSettings },
        { name: 'Position', component: PositionSettings },
        { name: 'Spacing', component: SpacingSettings },
        { name: 'Background', component: BackgroundSettings },
        { name: 'Border', component: BorderStyleSettings },
        { name: 'Decoration', component: DecorationSettings },
        { name: 'Custom styling', component: CustomStyleSettings },
        { name: 'Animations', component: AnimationSettings },
        { name: 'Transformations', component: TransformSettings },
        { name: 'Transitions', component: TransitionSettings },
    ],
};
