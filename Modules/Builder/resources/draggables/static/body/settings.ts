import AnimationSettings from '@modules/Builder/resources/components/settings/animation-settings.vue';
import Body from '@modules/Builder/resources/draggables/static/body/body.vue';

export default {
    component: Body,
    settings: [{ name: 'Animations', component: AnimationSettings }],
};
