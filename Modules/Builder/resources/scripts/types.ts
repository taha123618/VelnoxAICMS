import { CurrentState, DeviceType } from "@modules/Builder/resources/scripts/enums";
import VelnoxAIElement from "@modules/Builder/resources/scripts/VelnoxAI-element";
import type { Component } from "vue";

export type TDeviceStyles = {
  default: Record<string, any>;
  hover: Record<string, any>;
};

export type TGradientType = 'linear' | 'radial'

export type TBuilderType = "page" | "layout" | "post"

export type TTabName = "components" | "styling" | "settings" | "pages";

export type TTab = {
  name: TTabName;
  icon: string;
  label: string;
  component: Component;
};

export type TEditor = {
  selectedElement: VelnoxAIElement | null;
  cutOrCopiedElement: VelnoxAIElement | null;
  cutOrCopyAction: TCutOrCopyAction | null;
  device: DeviceType;
  state: CurrentState;
  isEnabled: boolean;
  showOutline: boolean;
  isPreviewing: boolean;
};

export type TElement = {
  category?: string;
  group?: string;
  canDrop: boolean;
  isSingleton?: boolean;
  isLayoutElement: boolean;
  id: string;
  type: string;
  name: string;
  icon: string;
  children: VelnoxAIElement[];
  props: Record<string, any>;
};
export type TCutOrCopyAction = "cut" | "copy" | null;

export type LinkType = "page" | "external" | "none" | undefined;

export type LinkTargetType = "_self" | "_blank" | undefined;

export type TLabelPosition = "top" | "left";

type ElStateType =
  | "idle"
  | "preview"
  | "is-dragging"
  | "is-dragging-over";

export type ElState = {
  type: ElStateType;
  container?: HTMLElement | null;
  closestEdge?: any | null | undefined;
};

export type TElementGroup = {
  id: string;
  name: string;
  components: TElement[];
};


export type TResizeType = 'width' | 'height';
export type THeightDir = 'top' | 'bottom';
export type TWidthDir = 'left' | 'right';