<template>
  <div class="space-y-1.5">
    <div class="h-2.5 overflow-hidden rounded-full" :class="bgClass">
      <div class="h-full rounded-full transition-all duration-500" :class="barClass" :style="{ width: percentage + '%' }" />
    </div>
    <div v-if="showLabels" class="flex items-center justify-between text-sm">
      <span class="font-semibold" :class="collectedClass">RM{{ collected.toLocaleString() }}</span>
      <span class="text-xs text-gray-400">{{ percentage }}% daripada RM{{ target.toLocaleString() }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  collected: { type: Number, default: 0 },
  target: { type: Number, default: 1 },
  showLabels: { type: Boolean, default: true },
  color: { type: String, default: 'emerald' },
})

const percentage = computed(() => Math.min(100, Math.round((props.collected / props.target) * 100)))

// Explicit class map instead of string interpolation - Tailwind's content
// scanner only picks up literal class name strings, not dynamically built ones.
const COLOR_CLASSES = {
  emerald: { bg: 'bg-emerald-100', bar: 'bg-emerald-500', text: 'text-emerald-600' },
  amber: { bg: 'bg-amber-100', bar: 'bg-amber-500', text: 'text-amber-600' },
  red: { bg: 'bg-red-100', bar: 'bg-red-500', text: 'text-red-600' },
  sky: { bg: 'bg-sky-100', bar: 'bg-sky-500', text: 'text-sky-600' },
}

const colorSet = computed(() => COLOR_CLASSES[props.color] || COLOR_CLASSES.emerald)
const bgClass = computed(() => colorSet.value.bg)
const barClass = computed(() => colorSet.value.bar)
const collectedClass = computed(() => colorSet.value.text)
</script>
