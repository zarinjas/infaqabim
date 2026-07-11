<template>
  <div class="space-y-1.5">
    <div class="h-2.5 overflow-hidden rounded-full" :class="bgClass">
      <div class="h-full rounded-full transition-all duration-500" :class="barClass" :style="{ width: percentage + '%' }" />
    </div>
    <div v-if="showLabels" class="flex items-center justify-between text-sm">
      <span class="font-medium" :class="collectedClass">RM{{ collected.toLocaleString() }}</span>
      <span class="text-gray-400">Goal RM{{ target.toLocaleString() }}</span>
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

const bgClass = computed(() => `bg-${props.color}-100`)
const barClass = computed(() => `bg-${props.color}-500`)
const collectedClass = computed(() => `text-${props.color}-600`)
</script>
