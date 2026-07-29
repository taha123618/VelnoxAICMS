<template>
  <div
    :class="[
      orientationStyles[edgeToOrientationMap[edge]],
      edgeStyles[edge],
      'before:content[\'\'] pointer-events-none absolute box-border bg-green-600 before:absolute before:h-[--terminal-size]x before:w-[--terminal-size]x before:rounded-fullx before:border-[length:--line-thickness]x before:border-solidx before:border-green-600x',
    ]"
    :style="{
      '--line-thickness': `${strokeSize}px`,
      '--line-offset': `calc(-0.5 * (${gap} + ${strokeSize}px))`,
      '--terminal-size': `${terminalSize}px`,
      '--terminal-radius': `${terminalSize / 2}px`,
      '--negative-terminal-size': `-${terminalSize}px`,
      '--offset-terminal': `${offsetToAlignTerminalWithLine}px`,
    }"
  />
</template>


<script lang="ts" setup>
export type Edge = "top" | "right" | "bottom" | "left";
export type Orientation = "horizontal" | "vertical";

interface Props {
  edge?: Edge;
  strokeSize?: number;
}

const { edge = "top", strokeSize = 2 } = defineProps<Props>();

const gap = "8px";

const edgeToOrientationMap: Record<Edge, Orientation> = {
  top: "horizontal",
  bottom: "horizontal",
  left: "vertical",
  right: "vertical",
};

const orientationStyles = {
  horizontal:
    "h-[var(--line-thickness)] left-[var(--terminal-radius)] right-0 before:left-[var(--negative-terminal-size)]",
  vertical:
    "w-[var(--line-thickness)] top-[var(--terminal-radius)] bottom-0 before:top-[var(--negative-terminal-size)]",
} as const;

const edgeStyles: Record<Edge, string> = {
  top: "top-[var(--line-offset)] before:top-[var(--offset-terminal)]",
  right: "right-[var(--line-offset)] before:right-[var(--offset-terminal)]",
  bottom: "bottom-[var(--line-offset)] before:bottom-[var(--offset-terminal)]",
  left: "left-[var(--line-offset)] before:left-[var(--offset-terminal)]",
};

const terminalSize = 0;
const offsetToAlignTerminalWithLine = (strokeSize - terminalSize) / 2;
</script>


<style scoped>
.drag-indicator {
  pointer-events: none;
  position: absolute;
  box-sizing: border-box;
}

.drag-indicator::before {
  content: "";
}
</style>
