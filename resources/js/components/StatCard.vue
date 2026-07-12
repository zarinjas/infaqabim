<template>
  <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition-shadow hover:shadow-md">
    <div class="flex items-center gap-4">
      <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl" :class="iconBg">
        <component :is="icon" class="h-5 w-5" :class="iconColor" />
      </div>
      <div class="min-w-0">
        <div class="truncate text-2xl font-bold text-gray-900">{{ value }}</div>
        <div class="truncate text-xs text-gray-400">{{ label }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  icon: { type: [Object, Function], required: true },
  color: { type: String, default: 'emerald' },
})

const COLORS = {
  emerald: { bg: 'bg-emerald-100', text: 'text-emerald-600' },
  blue: { bg: 'bg-blue-100', text: 'text-blue-600' },
  amber: { bg: 'bg-amber-100', text: 'text-amber-600' },
  purple: { bg: 'bg-purple-100', text: 'text-purple-600' },
  red: { bg: 'bg-red-100', text: 'text-red-600' },
}

const iconBg = computed(() => (COLORS[props.color] || COLORS.emerald).bg)
const iconColor = computed(() => (COLORS[props.color] || COLORS.emerald).text)
</script>
