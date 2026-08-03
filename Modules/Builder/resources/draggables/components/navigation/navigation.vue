<template>
    <div class="relative w-full">
        <BaseElementWrapper
            tag="div"
            class="!z-[100] flex justify-between"
            :element="element"
        >
            <div class="block">
                <a
                    v-if="
                        element.getProp('logo.display') &&
                        !!element.getProp('logo.src')
                    "
                    href="/"
                >
                    <img
                        :src="element.getProp('logo.src')"
                        class="logo"
                    />
                </a>
            </div>

            <div class="hidden @lg:flex h-full items-center justify-end @md:justify-between">
                <UNavigationMenu
                    v-if="selectedMenu"
                    :arrow="element.getProp('dropdown.showArrow')"
                    :ui="{
                        root: 'h-full [&>div]:h-full [&>div]:flex',
                        arrow: 'dropdown_arrow',
                        item: 'py-0 h-full',
                        link: `h-full before:rounded-none before:bg-transparent data-[state=open]:text-inherit data-[state=open]:before:bg-transparent hover:before:bg-transparent menu_list_item`,
                        viewport: 'menu_list_dropdown shadow-none rounded-none ring-0',
                        childLink: `!py-2 rounded-none hover:before:bg-transparent before:bg-transparent hover:before:rounded-none before:rounded-none menu_list_dropdown_item `,
                        childList: 'p-0'
                    }"
                    :external-icon="false"
                    content-orientation="vertical"
                    :items="(selectedMenu as Modules.Menu.Data.MenuData)?.items"
                    class="w-full justify-center"
                />
            </div>
            <button
                class="toggle_button cursor-pointer gap-1.5 flex @lg:hidden items-center"
                @click="showMobileMenu = true"
            >
                <UIcon :name="element.getProp('toggleButton.icon')" />
                {{ element.getProp('toggleButton.label') }}
            </button>

        </BaseElementWrapper>

        <USlideover
            :ui="{
                overlay: 'absolute',
                content: `absolute sm:ring-0 sm:shadow-2xl max-w-sm ${className}_mobile_menu`,
            }"
            :modal="false"
            :overlay="true"
            :dismissible="false"
            :side="mobileMenuSide"
            :close="{
                color: mobileMenuCloseButtonColor,
                variant: mobileMenuCloseButtonVariant,
                size: mobileMenuCloseButtonSize,
            }"
            :title="element.getProp('mobileMenu.menuTitle')"
            v-model:open="showMobileMenu"
            portal="#el__body"
        >
            <template #body>
                <div
                    class="h-full overflow-y-auto"
                    v-if="
                        selectedMenu && (selectedMenu as Modules.Menu.Data.MenuData)?.items
                    "
                >
                    <UNavigationMenu
                        :ui="{
                            root: 'bg-transparent',
                        }"
                        :external-icon="false"
                        orientation="vertical"
                        :items="(selectedMenu as Modules.Menu.Data.MenuData)?.items"
                        class="w-full"
                    >
                        <template #item="{ item }">
                            <div :class="`${className}_mobile_menu_link flex w-full justify-between`">
                                <span>{{ item.label }}</span>
                                <span v-if="item.children?.length">
                                    <UIcon name="ph:caret-down" />
                                </span>
                            </div>
                        </template>
                    </UNavigationMenu>
                </div>
            </template>
        </USlideover>
    </div>
</template>

<script setup lang="ts">
import BaseElementWrapper from '@modules/Builder/resources/components/base-element-wrapper.vue';
import VelnoxAIElement from '@modules/Builder/resources/scripts/VelnoxAI-element';
import { useNavigation } from '@modules/Builder/resources/draggables/components/navigation/use-navigation';
import { useHead } from '@unhead/vue';
import { useElement } from '@modules/Builder/resources/scripts/use-element';

const { element } = defineProps<{
    element: VelnoxAIElement;
}>();

const { getStyles, mobileMenuSide, mobileMenuCloseButtonSize, mobileMenuCloseButtonVariant, mobileMenuCloseButtonColor, showMobileMenu } = useNavigation(element);
const { className } = useElement(element)

const menus = computed<Modules.Menu.Data.MenuData[] | unknown>(
    () => (usePage().props.menus || []) as Modules.Menu.Data.MenuData,
);

const selectedMenu = computed<Modules.Menu.Data.MenuData | unknown>(() => {
    return (menus.value as Modules.Menu.Data.MenuData[])?.find(
        (m) => m.id == String(element.getProp('menuId')),
    );
});

useHead({
    style: [
        {
            textContent: computed(() => getStyles()),
            id: element.id,
        },
    ],
});


</script>

<style></style>
